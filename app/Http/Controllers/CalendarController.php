<?php

namespace App\Http\Controllers;

use App\Models\CalendarEvent;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    /**
     * Display full-page monthly calendar view with month/year navigation controls & aggregate overlay.
     */
    public function index(Request $request)
    {
        /** @var User $user */
        $user = auth()->user();

        $month = (int) $request->input('month', now()->month);
        $year = (int) $request->input('year', now()->year);

        $currentDate = Carbon::createFromDate($year, $month, 1);
        $prevMonthDate = $currentDate->copy()->subMonth();
        $nextMonthDate = $currentDate->copy()->addMonth();

        // Calendar Grid Matrix calculation
        $startOfMonth = $currentDate->copy()->startOfMonth();
        $endOfMonth = $currentDate->copy()->endOfMonth();

        $calendarStart = $startOfMonth->copy()->startOfWeek(Carbon::SUNDAY);
        $calendarEnd = $endOfMonth->copy()->endOfWeek(Carbon::SATURDAY);

        // Fetch Users for scheduling target
        if ($user->isSuperAdmin()) {
            $schedulableUsers = User::orderBy('name')->get();
        } else {
            // Users in user's organizations
            $orgIds = $user->organizations()->pluck('organizations.id');
            $schedulableUsers = User::whereHas('organizations', fn($q) => $q->whereIn('organizations.id', $orgIds))
                ->orderBy('name')
                ->get();
        }

        // Aggregate User Filter for Super Admin Overlay View
        $selectedUserIds = $request->input('filter_users', []);
        if (!is_array($selectedUserIds)) {
            $selectedUserIds = [$selectedUserIds];
        }

        // Query Events
        $query = CalendarEvent::with(['organizer', 'attendees'])
            ->where('start_time', '<=', $calendarEnd->endOfDay())
            ->where('end_time', '>=', $calendarStart->startOfDay());

        if ($user->isSuperAdmin()) {
            if (!empty($selectedUserIds)) {
                $query->where(function ($q) use ($selectedUserIds) {
                    $q->whereIn('organizer_id', $selectedUserIds)
                      ->orWhereHas('attendees', fn($q2) => $q2->whereIn('users.id', $selectedUserIds));
                });
            }
        } else {
            $query->where(function ($q) use ($user) {
                $q->where('organizer_id', $user->id)
                  ->orWhereHas('attendees', fn($q2) => $q2->where('users.id', $user->id));
            });
        }

        $events = $query->get();

        // Map events by date string (Y-m-d) for fast calendar cell rendering
        $eventsByDate = [];
        foreach ($events as $evt) {
            $evtDate = $evt->start_time->format('Y-m-d');
            $eventsByDate[$evtDate][] = $evt;
        }

        // Fetch Today's events specifically for the hourly timeline stream
        $todayDateStr = now()->format('Y-m-d');
        $todayEvents = CalendarEvent::with(['organizer', 'attendees'])
            ->where(function ($q) use ($user, $selectedUserIds) {
                if ($user->isSuperAdmin() && !empty($selectedUserIds)) {
                    $q->whereIn('organizer_id', $selectedUserIds)
                      ->orWhereHas('attendees', fn($q2) => $q2->whereIn('users.id', $selectedUserIds));
                } elseif (!$user->isSuperAdmin()) {
                    $q->where('organizer_id', $user->id)
                      ->orWhereHas('attendees', fn($q2) => $q2->where('users.id', $user->id));
                }
            })
            ->whereDate('start_time', '<=', $todayDateStr)
            ->whereDate('end_time', '>=', $todayDateStr)
            ->orderBy('start_time')
            ->get();

        return view('calendar.index', compact(
            'currentDate',
            'prevMonthDate',
            'nextMonthDate',
            'calendarStart',
            'calendarEnd',
            'eventsByDate',
            'todayEvents',
            'schedulableUsers',
            'selectedUserIds',
            'user'
        ));
    }

    /**
     * Schedule a new meeting / event with overlap conflict checking.
     */
    public function store(Request $request)
    {
        /** @var User $user */
        $user = auth()->user();
        if (!$user) {
            abort(403, 'Unauthorized.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'attendees' => 'nullable|array',
            'attendees.*' => 'exists:users,id',
            'override_conflict' => 'nullable|boolean',
            'recurrence_pattern' => 'nullable|in:none,daily,weekly,monthly',
            'recurrence_end_date' => 'nullable|date|after_or_equal:start_time',
        ]);

        $startTime = Carbon::parse($validated['start_time']);
        $endTime = Carbon::parse($validated['end_time']);

        $attendeeIds = $validated['attendees'] ?? [];
        if (!in_array($user->id, $attendeeIds)) {
            $attendeeIds[] = $user->id;
        }

        if (!$user->isSuperAdmin()) {
            $allowedUserIds = User::whereHas('organizations', fn($q) => $q->whereIn('organizations.id', $user->organizations()->pluck('organizations.id')))->pluck('id')->toArray();
            foreach ($validated['attendees'] ?? [] as $attId) {
                if (!in_array($attId, $allowedUserIds)) {
                    abort(403, 'Cannot schedule meetings with users outside your organization.');
                }
            }
        }

        $pattern = $validated['recurrence_pattern'] ?? 'none';
        $recurrenceEndDate = !empty($validated['recurrence_end_date']) ? Carbon::parse($validated['recurrence_end_date'])->endOfDay() : null;

        $occurrences = [];
        $currentStart = $startTime->copy();
        $currentEnd = $endTime->copy();

        if ($pattern === 'none' || !$recurrenceEndDate) {
            $occurrences[] = ['start' => $currentStart, 'end' => $currentEnd];
        } else {
            // Cap occurrences to prevent infinite loops / overload
            $maxOccurrences = 100;
            $count = 0;
            while ($currentStart <= $recurrenceEndDate && $count < $maxOccurrences) {
                $occurrences[] = ['start' => $currentStart->copy(), 'end' => $currentEnd->copy()];
                if ($pattern === 'daily') {
                    $currentStart->addDay();
                    $currentEnd->addDay();
                } elseif ($pattern === 'weekly') {
                    $currentStart->addWeek();
                    $currentEnd->addWeek();
                } elseif ($pattern === 'monthly') {
                    $currentStart->addMonth();
                    $currentEnd->addMonth();
                }
                $count++;
            }
        }

        // Check for conflicts across all occurrences
        $conflictingEvents = collect();
        foreach ($occurrences as $occ) {
            $conflicts = CalendarEvent::where(function ($q) use ($attendeeIds) {
                $q->whereIn('organizer_id', $attendeeIds)
                  ->orWhereHas('attendees', fn($q2) => $q2->whereIn('users.id', $attendeeIds));
            })
            ->where(function ($q) use ($occ) {
                $q->where('start_time', '<', $occ['end'])
                  ->where('end_time', '>', $occ['start']);
            })
            ->with('organizer')
            ->get();
            $conflictingEvents = $conflictingEvents->merge($conflicts);
        }
        $conflictingEvents = $conflictingEvents->unique('id');

        if ($conflictingEvents->count() > 0 && empty($validated['override_conflict'])) {
            $conflictDetails = $conflictingEvents->map(fn($e) => "'{$e->title}' ({$e->start_time->format('M d H:i')} - {$e->end_time->format('H:i')})")->implode(', ');
            
            return redirect()->back()
                ->withInput()
                ->with('conflict_warning', [
                    'message' => "Schedule Conflict Detected! Target user(s) already have overlapping event(s): {$conflictDetails}.",
                    'data' => $validated,
                ]);
        }

        $isSuperAdminEvent = $user->isSuperAdmin();
        $eventColor = $isSuperAdminEvent ? '#f43f5e' : '#008b8b';

        foreach ($occurrences as $occ) {
            $event = CalendarEvent::create([
                'organizer_id' => $user->id,
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'start_time' => $occ['start'],
                'end_time' => $occ['end'],
                'is_super_admin_event' => $isSuperAdminEvent,
                'color' => $eventColor,
            ]);

            if (!empty($validated['attendees'])) {
                $event->attendees()->sync($validated['attendees']);
            }
        }

        $user->logActivity('scheduled_event', "Scheduled event '{$validated['title']}' (" . count($occurrences) . " occurrences)", null);

        return redirect()->route('calendar.index', ['month' => $startTime->month, 'year' => $startTime->year])
            ->with('success', "Event '{$validated['title']}' scheduled successfully (" . count($occurrences) . " occurrences).");
    }

    /**
     * Display calendar event details page.
     */
    public function show(CalendarEvent $event)
    {
        /** @var User $user */
        $user = auth()->user();

        // Access control: User can view if Super Admin, Organizer, or Attendee
        $isAttendee = $event->attendees()->where('users.id', $user->id)->exists();
        if (!$user->isSuperAdmin() && $event->organizer_id !== $user->id && !$isAttendee) {
            abort(403, 'Unauthorized to view this calendar event.');
        }

        $event->load(['organizer', 'attendees']);

        return view('calendar.show', compact('event', 'user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CalendarEvent $event)
    {
        /** @var User $user */
        $user = auth()->user();

        if (!$user->isSuperAdmin() && $event->organizer_id !== $user->id) {
            abort(403, 'Only the organizer or a Super Admin can edit this event.');
        }

        if ($user->isSuperAdmin()) {
            $schedulableUsers = User::orderBy('name')->get();
        } else {
            $orgIds = $user->organizations()->pluck('organizations.id');
            $schedulableUsers = User::whereHas('organizations', fn($q) => $q->whereIn('organizations.id', $orgIds))
                ->orderBy('name')
                ->get();
        }

        $event->load('attendees');

        return view('calendar.edit', compact('event', 'schedulableUsers', 'user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CalendarEvent $event)
    {
        /** @var User $user */
        $user = auth()->user();

        if (!$user->isSuperAdmin() && $event->organizer_id !== $user->id) {
            abort(403, 'Only the organizer or a Super Admin can edit this event.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'attendees' => 'nullable|array',
            'attendees.*' => 'exists:users,id',
        ]);

        $event->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
        ]);

        if (isset($validated['attendees'])) {
            $attendeeIds = $validated['attendees'];
            if (!in_array($event->organizer_id, $attendeeIds)) {
                $attendeeIds[] = $event->organizer_id;
            }
            $event->attendees()->sync($attendeeIds);
        } else {
            $event->attendees()->sync([$event->organizer_id]);
        }

        $user->logActivity('updated', "Updated calendar event '{$event->title}'", $event);

        return redirect()->route('calendar.show', $event)->with('success', "Event updated successfully.");
    }

    /**
     * Soft delete calendar event.
     */
    public function destroy(CalendarEvent $event)
    {
        /** @var User $user */
        $user = auth()->user();
        if (!$user) {
            abort(403, 'Unauthorized.');
        }

        if (!$user->isSuperAdmin() && $event->organizer_id !== $user->id) {
            abort(403, 'Only the organizer or a Super Admin can delete this event.');
        }

        $title = $event->title;
        $event->delete();
        $user->logActivity('deleted', "Soft-deleted calendar event '{$title}'", $event);

        return redirect()->back()->with('success', "Calendar event '{$title}' soft-deleted.");
    }
}
