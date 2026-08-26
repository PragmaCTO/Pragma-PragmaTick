@props(['event', 'isSuperAdmin' => false])

<div style="background: var(--bg-surface-elevated); border: 1px solid var(--border-color); border-left: 4px solid {{ $event->color }}; border-radius: 8px; padding: 0.85rem 1rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.75rem;">
    <div>
        <div style="display: flex; align-items: center; gap: 0.6rem;">
            <strong style="font-size: 0.98rem;">
                <a href="{{ route('calendar.show', $event) }}" style="color: var(--text-main); text-decoration: none;">
                    {{ $event->title }}
                </a>
            </strong>
            @if($isSuperAdmin)
                <span class="tag tag-rose" style="font-size: 0.68rem;">[SUPER ADMIN]</span>
            @endif
        </div>

        <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.25rem; display: flex; gap: 1rem; align-items: center; flex-wrap: wrap;">
            @php
                $minutes = $event->start_time->diffInMinutes($event->end_time);
                $durationStr = $minutes >= 60 ? (round($minutes / 60, 1) . ' hrs') : ($minutes . ' mins');
            @endphp
            <span>Time: <strong style="color: var(--primary);">{{ $event->start_time->format('h:i A') }} - {{ $event->end_time->format('h:i A') }} ({{ $durationStr }})</strong></span>
            <span>Organizer: <strong>{{ $event->organizer->name }}</strong></span>
            <span>Attendees: <strong>{{ $event->attendees_count ?? $event->attendees->count() }}</strong></span>
        </div>

        @if($event->description)
            <p style="font-size: 0.82rem; color: var(--text-muted); margin-top: 0.35rem;">
                {{ Str::limit($event->description, 110) }}
            </p>
        @endif
    </div>

    <a href="{{ route('calendar.show', $event) }}" class="btn btn-primary" style="font-size: 0.76rem; padding: 0.25rem 0.65rem;">
        View Details &rarr;
    </a>
</div>
