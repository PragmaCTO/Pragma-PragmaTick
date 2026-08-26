<?php

namespace App\Http\Controllers;

use App\Models\CalendarEvent;
use App\Models\User;
use App\Http\Requests\StoreCalendarEventRequest;
use App\Http\Requests\UpdateCalendarEventRequest;
use App\Services\CalendarService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    protected CalendarService $calendarService;

    public function __construct(CalendarService $calendarService)
    {
        $this->calendarService = $calendarService;
    }
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
            ->withCount('attendees')
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

        // Fetch events specifically for the hourly timeline stream (defaults to today or selected date)
        $timelineDateStr = $request->input('date', now()->format('Y-m-d'));
        try {
            $timelineDate = \Carbon\Carbon::parse($timelineDateStr);
        } catch (\Exception $e) {
            $timelineDate = now();
            $timelineDateStr = $timelineDate->format('Y-m-d');
        }

        $todayEvents = CalendarEvent::with(['organizer', 'attendees'])
            ->withCount('attendees')
            ->where(function ($q) use ($user, $selectedUserIds) {
                if ($user->isSuperAdmin() && !empty($selectedUserIds)) {
                    $q->whereIn('organizer_id', $selectedUserIds)
                      ->orWhereHas('attendees', fn($q2) => $q2->whereIn('users.id', $selectedUserIds));
                } elseif (!$user->isSuperAdmin()) {
                    $q->where('organizer_id', $user->id)
                      ->orWhereHas('attendees', fn($q2) => $q2->where('users.id', $user->id));
                }
            })
            ->whereDate('start_time', '<=', $timelineDateStr)
            ->whereDate('end_time', '>=', $timelineDateStr)
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
            'timelineDateStr',
            'timelineDate',
            'schedulableUsers',
            'selectedUserIds',
            'user'
        ));
    }

    /**
     * Schedule a new meeting / event with overlap conflict checking.
     */
    public function store(StoreCalendarEventRequest $request)
    {
        /** @var User $user */
        $user = auth()->user();

        $result = $this->calendarService->createEvents($request->validated(), $user);

        if ($result->has('conflicts')) {
            $conflictingEvents = $result->get('conflicts');
            $conflictDetails = $conflictingEvents->map(fn($e) => "'{$e->title}' ({$e->start_time->format('M d H:i')} - {$e->end_time->format('H:i')})")->implode(', ');
            
            return redirect()->back()
                ->withInput()
                ->with('conflict_warning', [
                    'message' => "Schedule Conflict Detected! Target user(s) already have overlapping event(s): {$conflictDetails}.",
                    'data' => $request->validated(),
                ]);
        }

        return redirect()->route('calendar.index', ['month' => Carbon::parse($request->start_time)->month, 'year' => Carbon::parse($request->start_time)->year])
            ->with('success', "Event '{$request->title}' scheduled successfully (" . $result->count() . " occurrences).");
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

        $event->load(['organizer', 'attendees'])->loadCount('attendees');

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
    public function update(UpdateCalendarEventRequest $request, CalendarEvent $event)
    {
        $this->calendarService->updateEvent($event, $request->validated());

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

        return redirect()->back()->with('success', "Calendar event '{$title}' soft-deleted.");
    }
}
