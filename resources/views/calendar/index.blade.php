@extends('layouts.app')

@section('title', 'Calendar & Scheduling - PragmaTick Command Center')

@section('content')

<style>
    .calendar-header-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: 1rem;
        background: var(--bg-surface);
        padding: 1.25rem 1.5rem;
        border-radius: 12px;
        border: 1px solid var(--border-color);
        box-shadow: var(--card-shadow);
    }

    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 1px;
        background: var(--border-color);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: var(--card-shadow);
    }

    .calendar-day-head {
        background: var(--bg-surface-elevated);
        padding: 0.75rem;
        text-align: center;
        font-weight: 700;
        font-size: 0.85rem;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .calendar-day-cell {
        background: var(--bg-surface);
        min-height: 120px;
        padding: 0.6rem;
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
        transition: background-color 0.2s ease;
    }

    .calendar-day-cell.other-month {
        background: var(--bg-page);
        opacity: 0.5;
    }

    .calendar-day-cell.today {
        background: var(--primary-light);
    }

    .day-number {
        font-size: 0.88rem;
        font-weight: 800;
        margin-bottom: 0.2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .event-pill {
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 700;
        color: #ffffff;
        text-decoration: none;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        display: block;
    }

    .event-pill-rose {
        background: var(--accent-rose) !important;
        border-left: 3px solid #be123c;
    }

    /* Hourly Timeline Stream Styles */
    .timeline-hour-row {
        display: grid;
        grid-template-columns: 90px 1fr;
        gap: 1rem;
        padding: 0.75rem 0;
        border-bottom: 1px dashed var(--border-color);
        align-items: flex-start;
    }

    .timeline-hour-row.current-hour {
        background: rgba(32, 178, 170, 0.08);
        border-left: 3px solid var(--primary);
        padding-left: 0.5rem;
    }

    .timeline-hour-label {
        font-family: monospace;
        font-size: 0.82rem;
        font-weight: 700;
        color: var(--text-muted);
        text-align: right;
        padding-right: 0.5rem;
    }

    /* Custom Select2 Theme Integration */
    .select2-container--default .select2-selection--multiple {
        background-color: var(--bg-surface-elevated) !important;
        border: 1px solid var(--border-color) !important;
        border-radius: 8px !important;
        min-height: 42px !important;
        padding: 2px 6px !important;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: rgba(32, 178, 170, 0.15) !important;
        border: 1px solid var(--primary) !important;
        color: var(--primary) !important;
        border-radius: 6px !important;
        font-size: 0.8rem !important;
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

<!-- Overlap Conflict Warning Alert -->
@if(session('conflict_warning'))
    @php
        $conflictData = session('conflict_warning')['data'];
    @endphp
    <div style="background: rgba(245, 158, 11, 0.15); border: 1px solid var(--accent-amber); border-radius: 8px; padding: 1.25rem; margin-bottom: 1.5rem; color: var(--text-main);">
        <h3 style="color: var(--accent-amber); font-weight: 800; display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
            [SCHEDULE OVERLAP CONFLICT DETECTED]
        </h3>
        <p style="font-size: 0.9rem; margin-bottom: 1rem;">
            {{ session('conflict_warning')['message'] }}
        </p>

        <form action="{{ route('calendar.store') }}" method="POST" style="display: flex; gap: 1rem; align-items: center;">
            @csrf
            <input type="hidden" name="title" value="{{ $conflictData['title'] }}">
            <input type="hidden" name="description" value="{{ $conflictData['description'] ?? '' }}">
            <input type="hidden" name="start_time" value="{{ $conflictData['start_time'] }}">
            <input type="hidden" name="end_time" value="{{ $conflictData['end_time'] }}">
            @foreach($conflictData['attendees'] ?? [] as $att)
                <input type="hidden" name="attendees[]" value="{{ $att }}">
            @endforeach
            <input type="hidden" name="override_conflict" value="1">

            <button type="submit" class="btn btn-primary" style="background: var(--accent-amber); border: none;">
                Override Conflict & Schedule
            </button>
            <a href="{{ route('calendar.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endif

<!-- Header & View Switcher -->
<div class="calendar-header-bar">
    <div>
        <h1 style="font-size: 1.65rem; font-weight: 800;">
            {{ $currentDate->format('F Y') }}
        </h1>
        <p style="color: var(--text-muted); font-size: 0.88rem;">
            Schedule Planner for <strong>{{ $user->name }}</strong> | Today: <strong>{{ now()->format('l, M j, Y') }}</strong>
        </p>
    </div>

    <div style="display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap;">
        <!-- View Mode Segmented Switcher -->
        <div style="background: var(--bg-surface-elevated); padding: 0.2rem; border-radius: 8px; border: 1px solid var(--border-color); display: flex; gap: 0.2rem;">
            <button class="btn btn-primary" id="btnMonthlyView" onclick="switchCalendarView('monthly')" style="font-size: 0.78rem; padding: 0.35rem 0.75rem;">
                Monthly Grid
            </button>
            <button class="btn btn-secondary" id="btnTimelineView" onclick="switchCalendarView('timeline')" style="font-size: 0.78rem; padding: 0.35rem 0.75rem;">
                Timeline ({{ $todayEvents->count() }})
            </button>
        </div>

        <div style="display: flex; gap: 0.4rem;">
            <a href="{{ route('calendar.index', ['month' => $prevMonthDate->month, 'year' => $prevMonthDate->year]) }}" class="btn btn-secondary" style="font-size: 0.8rem; padding: 0.35rem 0.65rem;">
                &larr; {{ $prevMonthDate->format('M') }}
            </a>

            <a href="{{ route('calendar.index', ['month' => now()->month, 'year' => now()->year]) }}" class="btn btn-secondary" style="font-size: 0.8rem; padding: 0.35rem 0.65rem; font-weight: 800;">
                Today
            </a>

            <a href="{{ route('calendar.index', ['month' => $nextMonthDate->month, 'year' => $nextMonthDate->year]) }}" class="btn btn-secondary" style="font-size: 0.8rem; padding: 0.35rem 0.65rem;">
                {{ $nextMonthDate->format('M') }} &rarr;
            </a>
        </div>

        <button class="btn btn-primary" onclick="openScheduleModal('{{ now()->format('Y-m-d\TH:00') }}')">
            + Schedule Event
        </button>
    </div>
</div>

<!-- Super Admin Aggregate Overlay User Selector -->
@if($user->isSuperAdmin())
    <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 12px; padding: 1rem 1.25rem; margin-bottom: 1.5rem; box-shadow: var(--card-shadow);">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.75rem;">
            <div>
                <strong style="font-size: 0.95rem; color: var(--primary);">Super Admin Aggregate Calendar Overlay</strong>
                <p style="font-size: 0.82rem; color: var(--text-muted);">Select users to overlay their event schedules onto your aggregate grid.</p>
            </div>

            <form action="{{ route('calendar.index') }}" method="GET" style="display: flex; gap: 0.5rem; align-items: center;">
                <input type="hidden" name="month" value="{{ $currentDate->month }}">
                <input type="hidden" name="year" value="{{ $currentDate->year }}">
                
                <select name="filter_users[]" class="select2-filter" multiple style="width: 260px;">
                    @foreach($schedulableUsers as $su)
                        <option value="{{ $su->id }}" {{ in_array($su->id, $selectedUserIds) ? 'selected' : '' }}>
                            {{ $su->name }} {{ $su->isSuperAdmin() ? '[Super Admin]' : '' }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-secondary" style="font-size: 0.8rem; padding: 0.45rem 0.8rem;">Apply Filter</button>
            </form>
        </div>
    </div>
@endif

<!-- Mode 1: Full-Page Monthly Calendar Grid -->
<div id="monthlyViewContainer">
    <div class="calendar-grid">
        <div class="calendar-day-head">Sun</div>
        <div class="calendar-day-head">Mon</div>
        <div class="calendar-day-head">Tue</div>
        <div class="calendar-day-head">Wed</div>
        <div class="calendar-day-head">Thu</div>
        <div class="calendar-day-head">Fri</div>
        <div class="calendar-day-head">Sat</div>

        @php
            $iterDate = $calendarStart->copy();
        @endphp

        @while($iterDate <= $calendarEnd)
            @php
                $dateStr = $iterDate->format('Y-m-d');
                $isToday = $iterDate->isToday();
                $isCurrentMonth = $iterDate->month === $currentDate->month;
                $dayEvents = $eventsByDate[$dateStr] ?? [];
            @endphp

            <div class="calendar-day-cell {{ !$isCurrentMonth ? 'other-month' : '' }} {{ $isToday ? 'today' : '' }}">
                <div class="day-number">
                    <span>{{ $iterDate->day }}</span>
                    @if($isToday)
                        <span class="tag tag-cyan" style="font-size: 0.65rem;">Today</span>
                    @endif
                </div>

                @foreach($dayEvents as $evt)
                    @php
                        $isSuperAdminEvt = $evt->is_super_admin_event || ($evt->organizer && $evt->organizer->isSuperAdmin());
                    @endphp
                    <a href="{{ route('calendar.show', $evt) }}" class="event-pill {{ $isSuperAdminEvt ? 'event-pill-rose' : '' }}" 
                       style="{{ !$isSuperAdminEvt ? 'background:' . $evt->color : '' }}"
                       title="Click to view details: {{ $evt->title }} ({{ $evt->start_time->format('H:i') }} - {{ $evt->end_time->format('H:i') }}) | Organizer: {{ $evt->organizer->name }}">
                        
                        <span>{{ $evt->start_time->format('H:i') }} {{ $evt->title }}</span>
                        @if($isSuperAdminEvt)
                            <small style="font-size: 0.65rem;">[SA]</small>
                        @endif
                    </a>
                @endforeach
            </div>

            @php
                $iterDate->addDay();
            @endphp
        @endwhile
    </div>
</div>

<!-- Mode 2: Outlook-Style Visual Hourly Schedule Grid -->
<div id="todayTimelineContainer" style="display: none; background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 12px; padding: 1.5rem; box-shadow: var(--card-shadow);">
    
    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); padding-bottom: 1rem; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 0.75rem;">
        <div>
            <h2 style="font-size: 1.3rem; font-weight: 800; color: var(--primary);">
                Schedule Stream — {{ $timelineDate->format('l, F j, Y') }}
            </h2>
            <p style="font-size: 0.85rem; color: var(--text-muted); margin-top: 0.15rem;">
                Outlook-style visual timeline schedule for personal and team meetings
            </p>
        </div>
        <div style="display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap;">
            <div style="display: flex; align-items: center; gap: 0.4rem;">
                <label style="font-size: 0.82rem; font-weight: 700; color: var(--text-muted);">Select Date:</label>
                <input type="date" value="{{ $timelineDateStr }}" onchange="window.location.href='{{ route('calendar.index') }}?view=timeline&date=' + this.value" style="padding: 0.35rem 0.6rem; border-radius: 6px; border: 1px solid var(--border-color); background: var(--bg-surface-elevated); color: var(--text-main); font-weight: 600; font-size: 0.82rem;">
            </div>
            @if($timelineDateStr === now()->format('Y-m-d'))
                <span class="tag tag-cyan" style="font-size: 0.78rem; font-weight: 700; padding: 0.35rem 0.75rem;">
                    LIVE TIME: {{ now()->format('h:i A') }}
                </span>
            @endif
            <button type="button" class="btn btn-primary" onclick="openScheduleModal('{{ $timelineDateStr }}T09:00')" style="font-size: 0.8rem; padding: 0.35rem 0.75rem;">
                + Schedule Event
            </button>
        </div>
    </div>

    @php
        $rowHeight = 70; // 70px per 1 hour slot
        $startHour = 6;
        $endHour = 23;
        $totalHours = $endHour - $startHour + 1;
        $totalHeight = $totalHours * $rowHeight;

        $timelineEventsList = $todayEvents->map(function($e) use ($startHour, $endHour) {
            $startMins = max(0, ($e->start_time->hour - $startHour) * 60 + $e->start_time->minute);
            $endMins = min(($endHour - $startHour + 1) * 60, ($e->end_time->hour - $startHour) * 60 + $e->end_time->minute);
            if ($endMins <= $startMins) $endMins = $startMins + 30;
            return [
                'event' => $e,
                'startMins' => $startMins,
                'endMins' => $endMins,
                'topPx' => ($startMins / 60) * 70,
                'heightPx' => max(42, (($endMins - $startMins) / 60) * 70),
            ];
        })->sortBy('startMins')->values();

        $positionedEvents = [];
        foreach ($timelineEventsList as $item) {
            $overlaps = $timelineEventsList->filter(function($other) use ($item) {
                return $item['startMins'] < $other['endMins'] && $item['endMins'] > $other['startMins'];
            })->values();

            $totalCols = max(1, $overlaps->count());
            $colIndex = $overlaps->search(fn($o) => $o['event']->id === $item['event']->id);
            if ($colIndex === false) $colIndex = 0;

            $item['colIndex'] = $colIndex;
            $item['totalCols'] = $totalCols;
            $positionedEvents[] = $item;
        }

        $isToday = ($timelineDateStr === now()->format('Y-m-d'));
        $currentHour = now()->hour;
        $currentMinute = now()->minute;
        $nowTopPx = (($currentHour - $startHour) * 60 + $currentMinute) / 60 * $rowHeight;
        $isNowInRange = ($isToday && $currentHour >= $startHour && $currentHour <= $endHour);
    @endphp

    <!-- Visual Schedule Grid Container -->
    <div style="display: flex; position: relative; width: 100%; border: 1px solid var(--border-color); border-radius: 8px; overflow: hidden; background: var(--bg-surface-elevated);">
        
        <!-- Left Column: Hourly Time Labels -->
        <div style="width: 85px; flex-shrink: 0; background: var(--bg-surface); border-right: 1px solid var(--border-color); display: flex; flex-direction: column;">
            @for($h = $startHour; $h <= $endHour; $h++)
                @php
                    $hourLabel = Carbon\Carbon::createFromTime($h, 0)->format('h:00 A');
                    $isCurrent = ($isToday && $h === $currentHour);
                @endphp
                <div style="height: {{ $rowHeight }}px; border-bottom: 1px solid var(--border-color); padding: 0.4rem 0.6rem; font-size: 0.76rem; font-weight: 700; color: {{ $isCurrent ? 'var(--primary)' : 'var(--text-muted)' }}; box-sizing: border-box; display: flex; flex-direction: column; justify-content: flex-start; align-items: flex-start;">
                    <span>{{ $hourLabel }}</span>
                    @if($isCurrent)
                        <span style="font-size: 0.62rem; color: var(--accent-rose); font-weight: 800;">NOW</span>
                    @endif
                </div>
            @endfor
        </div>

        <!-- Right Column: Timeline Slot Rows & Floating Event Cards -->
        <div style="position: relative; flex: 1; height: {{ $totalHeight }}px; background: var(--bg-surface-elevated);">
            
            <!-- Hourly Horizontal Grid Background Lines -->
            @for($h = $startHour; $h <= $endHour; $h++)
                @php
                    $slotTimeStr = $timelineDateStr . 'T' . sprintf('%02d:00', $h);
                @endphp
                <div style="position: absolute; top: {{ ($h - $startHour) * $rowHeight }}px; left: 0; right: 0; height: {{ $rowHeight }}px; border-bottom: 1px solid var(--border-color); opacity: 0.35; box-sizing: border-box; cursor: pointer;" 
                     onclick="openScheduleModal('{{ $slotTimeStr }}')" title="Click to schedule meeting at {{ Carbon\Carbon::createFromTime($h, 0)->format('h:00 A') }}">
                </div>
            @endfor

            <!-- Live Red Time Indicator Line (If Today) -->
            @if($isNowInRange)
                <div style="position: absolute; top: {{ $nowTopPx }}px; left: 0; right: 0; height: 2px; background: #f43f5e; z-index: 25; pointer-events: none;">
                    <div style="position: absolute; left: -5px; top: -4px; width: 10px; height: 10px; border-radius: 50%; background: #f43f5e;"></div>
                </div>
            @endif

            <!-- Positioned Event Cards (Multi-hour Spanning & Overlap Aware) -->
            @forelse($positionedEvents as $pEvt)
                @php
                    $e = $pEvt['event'];
                    $widthPct = 100 / $pEvt['totalCols'];
                    $leftPct = $pEvt['colIndex'] * $widthPct;
                    $isSA = $e->is_super_admin_event || ($e->organizer && $e->organizer->isSuperAdmin());
                    $minutes = $e->start_time->diffInMinutes($e->end_time);
                    $durationStr = $minutes >= 60 ? (round($minutes / 60, 1) . ' hrs') : ($minutes . ' mins');
                    $isShort = ($pEvt['heightPx'] < 70);
                    $cardHeight = max(48, $pEvt['heightPx']);
                    $tooltipText = $e->title . ' (' . $e->start_time->format('h:i A') . ' - ' . $e->end_time->format('h:i A') . ' - ' . $durationStr . ')' . ($e->organizer ? "\nOrganizer: " . $e->organizer->name : '') . ($e->description ? "\n" . $e->description : '');
                @endphp
                <div style="position: absolute; top: {{ $pEvt['topPx'] }}px; height: {{ $cardHeight }}px; left: calc({{ $leftPct }}% + 3px); width: calc({{ $widthPct }}% - 6px); background: var(--bg-surface); border: 1px solid var(--border-color); border-left: 5px solid {{ $e->color }}; border-radius: 8px; padding: 0.35rem 0.6rem; box-shadow: 0 4px 12px rgba(0,0,0,0.12); overflow: hidden; z-index: 10; display: flex; flex-direction: column; justify-content: space-between; box-sizing: border-box; cursor: pointer; transition: transform 0.15s ease, box-shadow 0.15s ease;"
                     onclick="window.location.href='{{ route('calendar.show', $e) }}'"
                     title="{{ $tooltipText }}"
                     onmouseenter="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 6px 16px rgba(0,0,0,0.2)';"
                     onmouseleave="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.12)';">
                    
                    @if($isShort)
                        <!-- Compact Layout for Short Meetings (< 1 Hour) -->
                        <div style="display: flex; align-items: center; justify-content: space-between; gap: 0.4rem; width: 100%;">
                            <div style="display: flex; align-items: center; gap: 0.35rem; min-width: 0; flex: 1;">
                                <strong style="font-size: 0.81rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: var(--text-main);">
                                    {{ $e->title }}
                                </strong>
                                @if($isSA)
                                    <span class="tag tag-rose" style="font-size: 0.58rem; padding: 0.05rem 0.2rem; flex-shrink: 0;">[SA]</span>
                                @endif
                            </div>
                            <span style="font-size: 0.72rem; color: var(--primary); font-weight: 700; flex-shrink: 0; white-space: nowrap;">
                                {{ $e->start_time->format('h:i A') }} ({{ $durationStr }})
                            </span>
                        </div>
                    @else
                        <!-- Full Expanded Layout for Multi-hour Meetings (>= 1 Hour) -->
                        <div>
                            <div style="display: flex; align-items: center; justify-content: space-between; gap: 0.4rem;">
                                <strong style="font-size: 0.86rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: var(--text-main);">
                                    {{ $e->title }}
                                </strong>
                                @if($isSA)
                                    <span class="tag tag-rose" style="font-size: 0.6rem; padding: 0.05rem 0.25rem;">[SA]</span>
                                @endif
                            </div>
                            <div style="font-size: 0.75rem; color: var(--primary); font-weight: 700; margin-top: 0.15rem;">
                                {{ $e->start_time->format('h:i A') }} - {{ $e->end_time->format('h:i A') }} ({{ $durationStr }})
                            </div>
                            @if($cardHeight >= 85)
                                <div style="font-size: 0.73rem; color: var(--text-muted); margin-top: 0.2rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    Organizer: <strong>{{ $e->organizer->name }}</strong>
                                </div>
                            @endif
                        </div>
                        
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 0.15rem;">
                            <span style="font-size: 0.7rem; color: var(--text-muted);">👥 {{ $e->attendees_count ?? $e->attendees->count() }} attendees</span>
                            <span style="font-size: 0.7rem; font-weight: 700; color: var(--primary);">View Details &rarr;</span>
                        </div>
                    @endif
                </div>
            @empty
                <div style="position: absolute; top: 40%; left: 0; right: 0; text-align: center; color: var(--text-muted); font-style: italic; font-size: 0.9rem;">
                    No meetings scheduled for {{ $timelineDate->format('F j, Y') }}. Click anywhere on the timeline to schedule one!
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Schedule Event Modal -->
<div id="newEventModal" style="display: none; position: fixed; top:0; left:0; right:0; bottom:0; background: rgba(0,0,0,0.6); align-items:center; justify-content:center; z-index:900; backdrop-filter: blur(4px);">
    <div style="background: var(--bg-surface); padding: 2rem; border-radius: 14px; width: 90%; max-width: 520px; border: 1px solid var(--border-color);">
        <h3 style="margin-bottom: 1rem; font-weight: 800; color: var(--primary);">Schedule Event / Meeting</h3>
        
        <form action="{{ route('calendar.store') }}" method="POST">
            @csrf
            <div style="margin-bottom: 1rem;">
                <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Event Title</label>
                <input type="text" name="title" placeholder="e.g. Sprint Planning Meeting" required style="width:100%; padding:0.6rem; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main);">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Start Datetime</label>
                    <input type="datetime-local" id="inputStartTime" name="start_time" required value="{{ now()->format('Y-m-d\TH:00') }}" style="width:100%; padding:0.6rem; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main);">
                </div>
                <div>
                    <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">End Datetime</label>
                    <input type="datetime-local" id="inputEndTime" name="end_time" required value="{{ now()->addHour()->format('Y-m-d\TH:00') }}" style="width:100%; padding:0.6rem; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main);">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Recurrence Pattern</label>
                    <select name="recurrence_pattern" id="inputRecurrence" style="width:100%; padding:0.6rem; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main);" onchange="toggleRecurrenceEndDate(this)">
                        <option value="none">None (One-time)</option>
                        <option value="daily">Daily</option>
                        <option value="weekly">Weekly</option>
                        <option value="monthly">Monthly</option>
                    </select>
                </div>
                <div id="recurrenceEndContainer" style="display: none;">
                    <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Recurrence End Date</label>
                    <input type="date" name="recurrence_end_date" id="inputRecurrenceEnd" style="width:100%; padding:0.6rem; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main);">
                </div>
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Select Meeting Attendees (Org Scope)</label>
                <select name="attendees[]" class="select2-attendees" multiple style="width:100%;">
                    @foreach($schedulableUsers as $su)
                        <option value="{{ $su->id }}">{{ $su->name }} ({{ $su->email }})</option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Description / Agenda</label>
                <textarea name="description" rows="3" style="width:100%; padding:0.6rem; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main);"></textarea>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:0.75rem;">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('newEventModal').style.display='none'">Cancel</button>
                <button type="submit" class="btn btn-primary">Schedule Event</button>
            </div>
        </form>
    </div>
</div>

<script>
    function switchCalendarView(mode) {
        const monthly = document.getElementById('monthlyViewContainer');
        const timeline = document.getElementById('todayTimelineContainer');
        const btnM = document.getElementById('btnMonthlyView');
        const btnT = document.getElementById('btnTimelineView');

        if (mode === 'monthly') {
            monthly.style.display = 'block';
            timeline.style.display = 'none';
            btnM.className = 'btn btn-primary';
            btnT.className = 'btn btn-secondary';
        } else {
            monthly.style.display = 'none';
            timeline.style.display = 'block';
            btnM.className = 'btn btn-secondary';
            btnT.className = 'btn btn-primary';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('view') === 'timeline' || urlParams.has('date')) {
            switchCalendarView('timeline');
        }
    });

    function openScheduleModal(startIso) {
        if (startIso) {
            document.getElementById('inputStartTime').value = startIso;
            // Calculate endIso + 1 hour
            const dt = new Date(startIso);
            dt.setHours(dt.getHours() + 1);
            const pad = (num) => String(num).padStart(2, '0');
            const endIso = dt.getFullYear() + '-' + pad(dt.getMonth() + 1) + '-' + pad(dt.getDate()) + 'T' + pad(dt.getHours()) + ':00';
            document.getElementById('inputEndTime').value = endIso;
        }
        document.getElementById('newEventModal').style.display = 'flex';
    }

    function toggleRecurrenceEndDate(select) {
        const endContainer = document.getElementById('recurrenceEndContainer');
        const endInput = document.getElementById('inputRecurrenceEnd');
        if (select.value === 'none') {
            endContainer.style.display = 'none';
            endInput.removeAttribute('required');
        } else {
            endContainer.style.display = 'block';
            endInput.setAttribute('required', 'required');
        }
    }
</script>

<script>
    $(document).ready(function() {
        if ($.fn.select2) {
            $('.select2-filter').select2({
                placeholder: "Filter overlay users...",
                allowClear: true,
                width: '260px'
            });

            $('.select2-attendees').select2({
                placeholder: "Search and select meeting attendees...",
                allowClear: true,
                width: '100%',
                dropdownParent: $('#newEventModal')
            });
        }
    });
</script>
@endsection
