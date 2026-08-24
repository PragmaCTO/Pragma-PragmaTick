@extends('layouts.app')

@section('title', 'Edit Event - ' . $event->title)

@section('content')
<div style="margin-bottom: 1.5rem;">
    <a href="{{ route('calendar.show', $event) }}" style="color: var(--primary); text-decoration: none; font-weight: 600; font-size: 0.88rem;">
        &larr; Back to Event Details
    </a>
</div>

<div class="page-header-bar" style="margin-bottom: 1.5rem;">
    <div>
        <div style="display: flex; align-items: center; gap: 0.75rem;">
            <div style="width: 14px; height: 14px; border-radius: 4px; background: {{ $event->color }}; flex-shrink: 0;"></div>
            <h1 class="page-header-title">Edit Event: {{ $event->title }}</h1>
        </div>
    </div>
</div>

<div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 12px; padding: 2rem; box-shadow: var(--card-shadow); max-width: 800px;">
    <form action="{{ route('calendar.update', $event) }}" method="POST">
        @csrf
        @method('PUT')

        <div style="margin-bottom: 1.5rem;">
            <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.5rem; color: var(--text-main);">Event Title *</label>
            <input type="text" name="title" value="{{ old('title', $event->title) }}" required style="width:100%; padding:0.75rem; border-radius:8px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main); font-size:1rem;">
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
            <div>
                <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.5rem; color: var(--text-main);">Start Time *</label>
                <input type="datetime-local" name="start_time" value="{{ old('start_time', $event->start_time->format('Y-m-d\TH:i')) }}" required style="width:100%; padding:0.75rem; border-radius:8px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main);">
            </div>
            <div>
                <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.5rem; color: var(--text-main);">End Time *</label>
                <input type="datetime-local" name="end_time" value="{{ old('end_time', $event->end_time->format('Y-m-d\TH:i')) }}" required style="width:100%; padding:0.75rem; border-radius:8px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main);">
            </div>
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.5rem; color: var(--text-main);">Meeting Attendees</label>
            <select name="attendees[]" multiple style="width:100%; padding:0.75rem; border-radius:8px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main); min-height: 120px;">
                @foreach($schedulableUsers as $u)
                    <option value="{{ $u->id }}" {{ $event->attendees->contains('id', $u->id) ? 'selected' : '' }}>
                        {{ $u->name }} ({{ $u->email }})
                    </option>
                @endforeach
            </select>
            <p style="font-size:0.75rem; color:var(--text-muted); margin-top:0.3rem;">Hold CTRL (or CMD) to select multiple users. Organizer is always included.</p>
        </div>

        <div style="margin-bottom: 2rem;">
            <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.5rem; color: var(--text-main);">Description & Agenda</label>
            <textarea name="description" rows="5" style="width:100%; padding:0.75rem; border-radius:8px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main); font-size:0.95rem; resize: vertical;">{{ old('description', $event->description) }}</textarea>
        </div>

        <div style="display:flex; justify-content:flex-end; gap:1rem;">
            <a href="{{ route('calendar.show', $event) }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Update Event</button>
        </div>
    </form>
</div>
@endsection
