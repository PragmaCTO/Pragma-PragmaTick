@extends('layouts.app')

@section('title', $event->title . ' - Calendar Event Details')

@section('content')
<div style="margin-bottom: 1.5rem;">
    <a href="{{ route('calendar.index', ['month' => $event->start_time->month, 'year' => $event->start_time->year]) }}" style="color: var(--primary); text-decoration: none; font-weight: 600; font-size: 0.88rem;">
        &larr; Back to Monthly Calendar
    </a>
</div>

<!-- Universal Standardized Page Header Bar -->
<div class="page-header-bar" style="margin-bottom: 1.5rem;">
    <div>
        <div style="display: flex; align-items: center; gap: 0.75rem;">
            <div style="width: 14px; height: 14px; border-radius: 4px; background: {{ $event->color }}; flex-shrink: 0;"></div>
            <h1 class="page-header-title">{{ $event->title }}</h1>
        </div>
        <p class="page-header-subtext">
            Scheduled by <strong>{{ $event->organizer->name }}</strong> ({{ $event->organizer->email }})
        </p>
    </div>

    <div class="page-header-actions">
        <a href="{{ route('calendar.index', ['month' => $event->start_time->month, 'year' => $event->start_time->year]) }}" class="btn btn-secondary">
            &larr; Back to Calendar
        </a>
        @if($user->isSuperAdmin() || $event->organizer_id === $user->id)
            <a href="{{ route('calendar.edit', $event) }}" class="btn btn-primary" style="background-color: var(--primary);">
                Edit Event
            </a>
            <form action="{{ route('calendar.destroy', $event) }}" method="POST" onsubmit="return promptDelete('Calendar Event {{ addslashes($event->title) }}', this);" style="margin:0;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Soft Delete Event</button>
            </form>
        @endif
    </div>
</div>

<!-- Event Details Grid -->
<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; flex-wrap: wrap;">
    
    <!-- Left Column: Event Timing & Agenda -->
    <div>
        <!-- Event Schedule Card -->
        <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 12px; padding: 1.5rem; box-shadow: var(--card-shadow); margin-bottom: 1.5rem;">
            <h3 style="font-size: 1.1rem; font-weight: 800; margin-bottom: 1rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.6rem; color: var(--primary);">
                Schedule & Time Window
            </h3>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.25rem;">
                <div>
                    <span style="font-size: 0.76rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; display: block; margin-bottom: 0.2rem;">
                        Start Time
                    </span>
                    <strong style="font-size: 1.05rem; color: var(--text-main);">
                        {{ $event->start_time->format('l, M d, Y') }}
                    </strong>
                    <div style="font-size: 0.9rem; font-weight: 700; color: var(--primary); margin-top: 0.1rem;">
                        {{ $event->start_time->format('h:i A') }}
                    </div>
                </div>

                <div>
                    <span style="font-size: 0.76rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; display: block; margin-bottom: 0.2rem;">
                        End Time
                    </span>
                    <strong style="font-size: 1.05rem; color: var(--text-main);">
                        {{ $event->end_time->format('l, M d, Y') }}
                    </strong>
                    <div style="font-size: 0.9rem; font-weight: 700; color: var(--primary); margin-top: 0.1rem;">
                        {{ $event->end_time->format('h:i A') }}
                    </div>
                </div>

                <div>
                    <span style="font-size: 0.76rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; display: block; margin-bottom: 0.2rem;">
                        Total Duration
                    </span>
                    <strong style="font-size: 1.05rem; color: var(--text-main);">
                        {{ $event->start_time->diffForHumans($event->end_time, true) }}
                    </strong>
                </div>
            </div>
        </div>

        <!-- Description / Agenda Body -->
        <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 12px; padding: 1.5rem; box-shadow: var(--card-shadow);">
            <h3 style="font-size: 1.1rem; font-weight: 800; margin-bottom: 1rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.6rem;">
                Event Agenda & Description
            </h3>

            @if($event->description)
                <div style="font-size: 0.95rem; line-height: 1.7; color: var(--text-main); white-space: pre-wrap;">{{ $event->description }}</div>
            @else
                <p style="color: var(--text-muted); font-size: 0.88rem;">No detailed description provided for this meeting.</p>
            @endif
        </div>
    </div>

    <!-- Right Column: Attendees Roster & Meta -->
    <div>
        <!-- Meeting Attendees Card -->
        <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 12px; padding: 1.5rem; box-shadow: var(--card-shadow);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.6rem;">
                <h3 style="font-size: 1.1rem; font-weight: 800;">Meeting Attendees</h3>
                <strong style="font-size: 0.88rem; font-weight: 700; color: var(--primary);">
                    {{ $event->attendees_count ?? $event->attendees->count() }} People
                </strong>
            </div>

            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                <!-- Organizer Item -->
                <div style="background: var(--bg-surface-elevated); padding: 0.75rem 0.85rem; border-radius: 8px; border: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <strong style="font-size: 0.9rem; color: var(--text-main);">{{ $event->organizer->name }}</strong>
                        <div style="font-size: 0.78rem; color: var(--text-muted);">{{ $event->organizer->email }}</div>
                    </div>
                    <strong style="font-size: 0.76rem; color: var(--primary); text-transform: uppercase;">ORGANIZER</strong>
                </div>

                <!-- Invitees Items -->
                @foreach($event->attendees as $att)
                    @if($att->id !== $event->organizer_id)
                        <div style="background: var(--bg-surface-elevated); padding: 0.65rem 0.85rem; border-radius: 8px; border: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <strong style="font-size: 0.88rem; color: var(--text-main);">{{ $att->name }}</strong>
                                <div style="font-size: 0.78rem; color: var(--text-muted);">{{ $att->email }}</div>
                            </div>
                            <strong style="font-size: 0.74rem; color: var(--text-muted); text-transform: uppercase;">ATTENDEE</strong>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

</div>
@endsection
