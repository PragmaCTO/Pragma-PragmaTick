@extends('layouts.app')

@section('title', 'Edit Event - ' . $event->title)

@section('content')
<!-- Custom Select2 Theme Integration -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--multiple {
        background-color: var(--bg-surface-elevated) !important;
        border: 1px solid var(--border-color) !important;
        border-radius: 8px !important;
        min-height: 44px !important;
        padding: 4px 8px !important;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: rgba(32, 178, 170, 0.15) !important;
        border: 1px solid var(--primary) !important;
        color: var(--primary) !important;
        border-radius: 6px !important;
        font-size: 0.82rem !important;
        font-weight: 700 !important;
        padding: 2px 8px !important;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: var(--primary) !important;
        margin-right: 5px !important;
        border-right: 1px solid rgba(32, 178, 170, 0.3) !important;
        padding-right: 4px !important;
    }
    .select2-dropdown {
        background-color: var(--bg-surface) !important;
        border: 1px solid var(--border-color) !important;
        border-radius: 8px !important;
        box-shadow: 0 10px 25px rgba(0,0,0,0.3) !important;
        z-index: 9999 !important;
    }
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: var(--primary) !important;
        color: #ffffff !important;
    }
    .select2-container--default .select2-results__option[aria-selected=true] {
        background-color: var(--bg-surface-elevated) !important;
        color: var(--primary) !important;
    }
    .select2-results__option {
        color: var(--text-main) !important;
        font-size: 0.85rem !important;
        padding: 6px 12px !important;
    }
    .select2-search__field {
        color: var(--text-main) !important;
    }
</style>

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
            <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.5rem; color: var(--text-main);">Select Meeting Attendees (Org Scope)</label>
            <select name="attendees[]" class="select2-edit-attendees" multiple style="width:100%;">
                @foreach($schedulableUsers as $u)
                    <option value="{{ $u->id }}" {{ $event->attendees->contains('id', $u->id) ? 'selected' : '' }}>
                        {{ $u->name }} ({{ $u->email }})
                    </option>
                @endforeach
            </select>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        if ($.fn.select2) {
            $('.select2-edit-attendees').select2({
                placeholder: "Search and select meeting attendees...",
                allowClear: true,
                width: '100%'
            });
        }
    });
</script>
@endsection
