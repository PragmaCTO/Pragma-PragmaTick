<?php

namespace App\Services;

use App\Models\CalendarEvent;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class CalendarService
{
    public function createEvents(array $validated, User $user): Collection
    {
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
            return collect(['conflicts' => $conflictingEvents]);
        }

        $isSuperAdminEvent = $user->isSuperAdmin();
        $eventColor = $isSuperAdminEvent ? '#f43f5e' : '#008b8b';

        // Dispatch background queue job for heavy processing
        \App\Jobs\GenerateRecurringCalendarEvents::dispatch($occurrences, $validated, $user, $eventColor, $isSuperAdminEvent);

        // Return the occurrences collection so the controller can count them for the flash message
        return collect($occurrences);
    }

    public function updateEvent(CalendarEvent $event, array $validated): void
    {
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
    }
}
