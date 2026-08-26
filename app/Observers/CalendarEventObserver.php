<?php

namespace App\Observers;

use App\Models\CalendarEvent;

class CalendarEventObserver
{
    /**
     * Handle the CalendarEvent "created" event.
     */
    public function created(CalendarEvent $calendarEvent): void
    {
        $user = auth()->user();
        if ($user) {
            $user->logActivity('scheduled_event', "Scheduled event '{$calendarEvent->title}'", $calendarEvent);
        }
    }

    /**
     * Handle the CalendarEvent "updated" event.
     */
    public function updated(CalendarEvent $calendarEvent): void
    {
        $user = auth()->user();
        if ($user) {
            $user->logActivity('updated', "Updated event '{$calendarEvent->title}'", $calendarEvent);
        }
    }

    /**
     * Handle the CalendarEvent "deleted" event.
     */
    public function deleted(CalendarEvent $calendarEvent): void
    {
        $user = auth()->user();
        if ($user) {
            $user->logActivity('deleted', "Soft-deleted event '{$calendarEvent->title}'", clone $calendarEvent);
        }
    }

    /**
     * Handle the CalendarEvent "restored" event.
     */
    public function restored(CalendarEvent $calendarEvent): void
    {
        //
    }

    /**
     * Handle the CalendarEvent "force deleted" event.
     */
    public function forceDeleted(CalendarEvent $calendarEvent): void
    {
        //
    }
}
