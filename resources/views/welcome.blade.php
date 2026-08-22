@extends('layouts.app')

@section('title', 'Overview Dashboard - PragmaTick Command Center')

@section('content')
<!-- Hero Dashboard Banner & Digital Clocks -->
<div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-left: 5px solid var(--primary); border-radius: 12px; padding: 1.5rem 1.75rem; margin-bottom: 2rem; box-shadow: var(--card-shadow); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.5rem;">
    <div>
        <h1 style="font-size: 1.75rem; font-weight: 800; color: var(--text-main);">Executive Dashboard</h1>
        <p style="color: var(--text-muted); font-size: 0.92rem; margin-top: 0.35rem;">
            Enterprise system overview &amp; real-time operational metrics &bull; <strong>{{ $user->name }}</strong> (<span style="color: var(--primary); font-weight: 600;">{{ $user->isSuperAdmin() ? 'Super Administrator' : 'Workspace Member' }}</span>)
        </p>
    </div>

    <!-- Dual Live Digital Clocks -->
    <div style="display: flex; gap: 1.25rem; flex-wrap: wrap; align-items: center;">
        <!-- Clock 1: Nepal Local Time (UTC+5:45) -->
        <div style="background: var(--bg-surface-elevated); border: 1px solid var(--border-color); border-radius: 10px; padding: 0.85rem 1.2rem; min-width: 230px; display: flex; flex-direction: column; justify-content: space-between; min-height: 98px;">
            <div style="display: flex; justify-content: space-between; align-items: center; height: 26px;">
                <span style="font-size: 0.72rem; font-weight: 700; color: var(--primary); text-transform: uppercase; letter-spacing: 0.05em;">Nepal Time</span>
                <span class="tag tag-cyan" style="font-size: 0.65rem;">UTC+05:45</span>
            </div>
            <div id="nepalClock" style="font-size: 1.4rem; font-weight: 800; font-family: monospace; color: var(--text-main); line-height: 1.2; margin: 0.2rem 0;">
                --:--:--
            </div>
            <div id="nepalDate" style="font-size: 0.72rem; color: var(--text-muted);">
                Kathmandu Local
            </div>
        </div>

        <!-- Clock 2: Searchable Interactive World Clock with UTC Offsets (Light Grey Cyan Shade) -->
        <div style="background: rgba(32, 178, 170, 0.08); border: 1px solid rgba(32, 178, 170, 0.28); border-radius: 10px; padding: 0.85rem 1.2rem; min-width: 310px; display: flex; flex-direction: column; justify-content: space-between; min-height: 98px;">
            <div style="display: flex; justify-content: space-between; align-items: center; gap: 0.5rem; height: 26px; position: relative;">
                <span style="font-size: 0.72rem; font-weight: 800; color: var(--primary); text-transform: uppercase; letter-spacing: 0.06em; flex-shrink: 0;">World Clock</span>
                
                <div style="position: relative; width: 100%; max-width: 210px;">
                    <input type="text" id="tzSearchInput" onfocus="showTzCombobox()" onkeyup="filterTzCombobox()" placeholder="SEARCH TIMEZONE..." style="font-family: 'JetBrains Mono', monospace; font-size: 0.72rem; font-weight: 700; padding: 0.2rem 0.6rem; border-radius: 6px; border: 1px solid var(--primary); background: var(--primary-light); color: var(--primary); width: 100%; outline: none; text-transform: uppercase; letter-spacing: 0.03em;" value="UTC">
                    <input type="hidden" id="timezoneSelect" value="UTC">

                    <div id="tzComboboxPanel" style="display: none; position: absolute; right: 0; top: 100%; margin-top: 0.35rem; width: 280px; max-height: 230px; overflow-y: auto; background: var(--bg-surface); border: 1px solid var(--primary); border-radius: 10px; box-shadow: var(--shadow-lg); z-index: 999;">
                        @if(isset($formattedTimezones))
                            @foreach($formattedTimezones as $tzItem)
                                <div class="tz-option-item" data-value="{{ $tzItem['id'] }}" data-label="{{ $tzItem['label'] }}" data-search="{{ $tzItem['search'] }}" onclick="selectComboboxTz('{{ $tzItem['id'] }}', '{{ addslashes($tzItem['label']) }}')" style="padding: 0.45rem 0.75rem; font-size: 0.76rem; font-weight: 600; cursor: pointer; border-bottom: 1px solid var(--border-color); display: flex; align-items: center; justify-content: space-between; gap: 0.5rem; transition: all 0.15s ease;">
                                    <span style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap; color: var(--text-main);">{{ $tzItem['id'] }}</span>
                                    <span class="tag tag-cyan" style="font-size: 0.62rem; flex-shrink: 0; padding: 0.1rem 0.35rem;">{{ $tzItem['offset'] }}</span>
                                </div>
                            @endforeach
                        @else
                            @foreach($allTimezones as $tzOption)
                                <div class="tz-option-item" data-value="{{ $tzOption }}" data-label="{{ $tzOption }}" data-search="{{ strtolower($tzOption) }}" onclick="selectComboboxTz('{{ $tzOption }}', '{{ $tzOption }}')" style="padding: 0.45rem 0.75rem; font-size: 0.76rem; font-weight: 600; cursor: pointer; border-bottom: 1px solid var(--border-color);">
                                    <span style="color: var(--text-main);">{{ $tzOption }}</span>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
            <div id="worldClock" style="font-size: 1.4rem; font-weight: 800; font-family: monospace; color: var(--text-main); line-height: 1.2; margin: 0.2rem 0;">
                --:--:--
            </div>
            <div id="worldDate" style="font-size: 0.72rem; color: var(--text-muted);">
                Selected Timezone
            </div>
        </div>
    </div>
</div>

<!-- Scoped Metric Buckets Grid -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); gap: 1.25rem; margin-bottom: 2rem;">
    <!-- Metric 1: Tasks -->
    <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 12px; padding: 1.25rem; box-shadow: var(--card-shadow);">
        <div style="font-size: 0.75rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.4rem;">
            Project Tasks
        </div>
        <div style="font-size: 1.85rem; font-weight: 800; color: var(--primary);">
            {{ $pendingTasksCount }} <span style="font-size: 0.95rem; font-weight: 600; color: var(--text-muted);">/ {{ $totalTasksCount }}</span>
        </div>
        <div style="font-size: 0.78rem; color: var(--text-muted); margin-top: 0.3rem;">
            Pending vs Total Active
        </div>
    </div>

    <!-- Metric 2: Organizations -->
    <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 12px; padding: 1.25rem; box-shadow: var(--card-shadow);">
        <div style="font-size: 0.75rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.4rem;">
            Organizations
        </div>
        <div style="font-size: 1.85rem; font-weight: 800; color: var(--accent-green);">
            {{ $orgsCount }}
        </div>
        <div style="font-size: 0.78rem; color: var(--text-muted); margin-top: 0.3rem;">
            Enterprise Organizations
        </div>
    </div>

    <!-- Metric 3: Projects -->
    <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 12px; padding: 1.25rem; box-shadow: var(--card-shadow);">
        <div style="font-size: 0.75rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.4rem;">
            Active Projects
        </div>
        <div style="font-size: 1.85rem; font-weight: 800; color: var(--primary);">
            {{ $projectsCount }}
        </div>
        <div style="font-size: 0.78rem; color: var(--text-muted); margin-top: 0.3rem;">
            Active Projects
        </div>
    </div>

    <!-- Metric 4: Milestones -->
    <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 12px; padding: 1.25rem; box-shadow: var(--card-shadow);">
        <div style="font-size: 0.75rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.4rem;">
            Upcoming Milestones
        </div>
        <div style="font-size: 1.85rem; font-weight: 800; color: var(--accent-amber);">
            {{ $upcomingMilestonesCount }}
        </div>
        <div style="font-size: 0.78rem; color: var(--text-muted); margin-top: 0.3rem;">
            Scheduled Releases
        </div>
    </div>

    <!-- Metric 5: Personal Checklist -->
    <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 12px; padding: 1.25rem; box-shadow: var(--card-shadow);">
        <div style="font-size: 0.75rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.4rem;">
            Personal Checklist
        </div>
        <div style="font-size: 1.85rem; font-weight: 800; color: var(--primary);">
            {{ $pendingChecklistCount }} <span style="font-size: 0.95rem; font-weight: 600; color: var(--text-muted);">/ {{ $totalChecklistCount }}</span>
        </div>
        <div style="font-size: 0.78rem; color: var(--text-muted); margin-top: 0.3rem;">
            Pending vs Total Items
        </div>
    </div>
</div>

<!-- 'My Day' Agenda Aggregation Section -->
<div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 12px; padding: 1.75rem; box-shadow: var(--card-shadow); margin-bottom: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.75rem;">
        <div>
            <h3 style="font-size: 1.25rem; font-weight: 800;">My Day Agenda</h3>
            <p style="font-size: 0.85rem; color: var(--text-muted);">Today's scheduled meetings, active project tasks, and personal checklist items</p>
        </div>
        <div style="font-size: 0.88rem; font-weight: 700; color: var(--text-main); font-family: 'JetBrains Mono', monospace; letter-spacing: -0.01em; display: flex; align-items: center; gap: 0.45rem; background: var(--bg-surface-elevated); padding: 0.35rem 0.75rem; border-radius: 6px; border: 1px solid var(--border-color); box-shadow: var(--shadow-xs);">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2.5"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            <span>{{ now()->format('l, F d, Y') }}</span>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
        
        <!-- Agenda Column 1: Today's Meetings -->
        <div style="background: var(--bg-surface-elevated); border: 1px solid var(--border-color); border-radius: 10px; padding: 1.25rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem;">
                <h4 style="font-size: 0.95rem; font-weight: 700; color: var(--primary);">Scheduled Meetings</h4>
                <span style="font-size: 1.15rem; font-weight: 800; color: var(--primary);">{{ $myDayEvents->count() }}</span>
            </div>

            <div style="display: flex; flex-direction: column; gap: 0.6rem;">
                @forelse($myDayEvents as $evt)
                    <div style="background: var(--bg-surface); padding: 0.6rem 0.8rem; border-radius: 6px; border: 1px solid var(--border-color); border-left: 3px solid {{ $evt->color }}; font-size: 0.84rem;">
                        <strong>{{ $evt->title }}</strong>
                        <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.2rem;">
                            Time: {{ $evt->start_time->format('H:i') }} - {{ $evt->end_time->format('H:i') }} | Org: {{ $evt->organizer->name }}
                        </div>
                    </div>
                @empty
                    <p style="color: var(--text-muted); font-size: 0.82rem;">No meetings scheduled for today.</p>
                @endforelse
            </div>
        </div>

        <!-- Agenda Column 2: Active Project Tasks -->
        <div style="background: var(--bg-surface-elevated); border: 1px solid var(--border-color); border-radius: 10px; padding: 1.25rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem;">
                <h4 style="font-size: 0.95rem; font-weight: 700; color: var(--primary);">Active Project Tasks</h4>
                <span style="font-size: 1.15rem; font-weight: 800; color: var(--accent-green);">{{ $myDayTasks->count() }}</span>
            </div>

            <div style="display: flex; flex-direction: column; gap: 0.6rem;">
                @forelse($myDayTasks as $t)
                    <div style="background: var(--bg-surface); padding: 0.6rem 0.8rem; border-radius: 6px; border: 1px solid var(--border-color); font-size: 0.84rem; display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <span class="tag tag-green" style="font-size: 0.65rem;">{{ $t->code }}</span>
                            <strong style="display: inline-block; margin-left: 0.3rem;">{{ Str::limit($t->title, 25) }}</strong>
                        </div>
                        <span class="tag tag-amber" style="font-size: 0.65rem;">{{ $t->status }}</span>
                    </div>
                @empty
                    <p style="color: var(--text-muted); font-size: 0.82rem;">No active project tasks assigned for today.</p>
                @endforelse
            </div>
        </div>

        <!-- Agenda Column 3: Personal Checklist Items -->
        <div style="background: var(--bg-surface-elevated); border: 1px solid var(--border-color); border-radius: 10px; padding: 1.25rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem;">
                <h4 style="font-size: 0.95rem; font-weight: 700; color: var(--primary);">Personal Checklist</h4>
                <span style="font-size: 1.15rem; font-weight: 800; color: var(--primary);">{{ $myDayChecklist->count() }}</span>
            </div>

            <div style="display: flex; flex-direction: column; gap: 0.6rem;">
                @forelse($myDayChecklist as $chk)
                    <div style="background: var(--bg-surface); padding: 0.6rem 0.8rem; border-radius: 6px; border: 1px solid var(--border-color); font-size: 0.84rem; display: flex; justify-content: space-between; align-items: center;">
                        <span>{{ Str::limit($chk->title, 28) }}</span>
                        <span class="tag tag-amber" style="font-size: 0.65rem;">{{ $chk->status }}</span>
                    </div>
                @empty
                    <p style="color: var(--text-muted); font-size: 0.82rem;">No pending checklist items.</p>
                @endforelse
            </div>
        </div>

    </div>
</div>

<script>
    // Live Nepal Clock (Asia/Kathmandu UTC+5:45)
    function updateNepalClock() {
        const options = { timeZone: 'Asia/Kathmandu', hour12: false, hour: '2-digit', minute: '2-digit', second: '2-digit' };
        const dateOpt = { timeZone: 'Asia/Kathmandu', weekday: 'short', month: 'short', day: 'numeric', year: 'numeric' };
        const now = new Date();
        document.getElementById('nepalClock').textContent = now.toLocaleTimeString('en-US', options);
        document.getElementById('nepalDate').textContent = now.toLocaleDateString('en-US', dateOpt) + ' (Kathmandu)';
    }

    // Live Interactive World Clock
    function updateWorldClock() {
        const selectEl = document.getElementById('timezoneSelect');
        const tz = selectEl ? selectEl.value : 'UTC';
        try {
            const options = { timeZone: tz, hour12: false, hour: '2-digit', minute: '2-digit', second: '2-digit' };
            const dateOpt = { timeZone: tz, weekday: 'short', month: 'short', day: 'numeric', year: 'numeric' };
            const now = new Date();
            document.getElementById('worldClock').textContent = now.toLocaleTimeString('en-US', options);
            document.getElementById('worldDate').textContent = now.toLocaleDateString('en-US', dateOpt) + ' (' + tz + ')';
        } catch(e) {
            console.log('Timezone rendering fallback:', e);
        }
    }

    function showTzCombobox() {
        const panel = document.getElementById('tzComboboxPanel');
        if (panel) panel.style.display = 'block';
    }

    function filterTzCombobox() {
        const input = document.getElementById('tzSearchInput');
        const query = input ? input.value.toLowerCase() : '';
        const panel = document.getElementById('tzComboboxPanel');
        if (panel) panel.style.display = 'block';
        
        const items = document.querySelectorAll('.tz-option-item');
        items.forEach(item => {
            const searchData = (item.getAttribute('data-search') || item.getAttribute('data-label') || '').toLowerCase();
            if (searchData.includes(query)) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    }

    function selectComboboxTz(tzId, tzLabel) {
        document.getElementById('timezoneSelect').value = tzId;
        const input = document.getElementById('tzSearchInput');
        if (input) input.value = tzLabel;
        const panel = document.getElementById('tzComboboxPanel');
        if (panel) panel.style.display = 'none';
        saveAndUpdateWorldClock();
    }

    window.addEventListener('click', function(e) {
        if (!e.target.closest('#tzSearchInput') && !e.target.closest('#tzComboboxPanel')) {
            const panel = document.getElementById('tzComboboxPanel');
            if (panel) panel.style.display = 'none';
        }
    });

    function saveAndUpdateWorldClock() {
        const selectedTz = document.getElementById('timezoneSelect').value;
        localStorage.setItem('pragmatick_world_tz', selectedTz);
        updateWorldClock();
    }

    // Load saved user timezone from localStorage
    const savedWorldTz = localStorage.getItem('pragmatick_world_tz');
    if (savedWorldTz && document.getElementById('timezoneSelect')) {
        document.getElementById('timezoneSelect').value = savedWorldTz;
        const item = document.querySelector(`.tz-option-item[data-value="${savedWorldTz}"]`);
        if (item && document.getElementById('tzSearchInput')) {
            document.getElementById('tzSearchInput').value = item.getAttribute('data-label');
        } else if (document.getElementById('tzSearchInput')) {
            document.getElementById('tzSearchInput').value = savedWorldTz;
        }
    }

    setInterval(updateNepalClock, 1000);
    setInterval(updateWorldClock, 1000);
    updateNepalClock();
    updateWorldClock();
</script>
@endsection
