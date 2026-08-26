<?php

namespace App\Jobs;

use App\Models\CalendarEvent;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;
use Illuminate\Support\Facades\Log;

class GenerateRecurringCalendarEvents implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected array $occurrences;
    protected array $validatedData;
    protected User $user;
    protected string $eventColor;
    protected bool $isSuperAdminEvent;

    /**
     * Create a new job instance.
     */
    public function __construct(array $occurrences, array $validatedData, User $user, string $eventColor, bool $isSuperAdminEvent)
    {
        $this->occurrences = $occurrences;
        $this->validatedData = $validatedData;
        $this->user = $user;
        $this->eventColor = $eventColor;
        $this->isSuperAdminEvent = $isSuperAdminEvent;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        foreach ($this->occurrences as $occ) {
            $event = CalendarEvent::create([
                'organizer_id' => $this->user->id,
                'title' => $this->validatedData['title'],
                'description' => $this->validatedData['description'] ?? null,
                'start_time' => $occ['start'],
                'end_time' => $occ['end'],
                'is_super_admin_event' => $this->isSuperAdminEvent,
                'color' => $this->eventColor,
            ]);

            if (!empty($this->validatedData['attendees'])) {
                $event->attendees()->sync($this->validatedData['attendees']);
            }
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(Throwable $exception): void
    {
        Log::error("Failed to generate recurring calendar events for User {$this->user->id}: " . $exception->getMessage());
    }
}
