@extends('layouts.app')

@section('title', 'Projects - PragmaTick Command Center')

@section('content')
@php
    $canCreateProjects = $canCreateProjects ?? false;
    $projectsByOrg = $projectsByOrg ?? [];
@endphp
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem; background: var(--bg-surface); padding: 1.25rem 1.5rem; border-radius: 12px; border: 1px solid var(--border-color); box-shadow: var(--shadow-sm);">
    <div>
        <h1 style="font-size: 1.65rem; font-weight: 800;">Projects Directory</h1>
        <p style="color: var(--text-muted); font-size: 0.88rem; margin-top: 0.2rem;">
            Enterprise projects grouped by organization
        </p>
    </div>

    @if($canCreateProjects)
        <button class="btn btn-primary" onclick="document.getElementById('createProjectModal').style.display='flex'">
            + Create Project
        </button>
    @endif
</div>

<!-- Projects Grouped by Organization -->
<div style="display: flex; flex-direction: column; gap: 2rem;">
    @forelse($projectsByOrg as $orgId => $group)
        @php
            $org = $group['organization'];
            $projects = $group['projects'];
        @endphp

        <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 12px; padding: 1.5rem; box-shadow: var(--card-shadow);">
            
            <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 2.5px solid {{ $org->color_code }}; padding-bottom: 0.85rem; margin-bottom: 1rem;">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <div style="width: 14px; height: 14px; border-radius: 3px; background: {{ $org->color_code }};"></div>
                    <h2 style="font-size: 1.25rem; font-weight: 800;">
                        <a href="{{ route('organizations.show', $org) }}" style="color: inherit; text-decoration: none;">
                            {{ $org->name }}
                        </a>
                    </h2>
                </div>
                <strong style="font-weight: 700; color: var(--primary); font-size: 0.88rem;">{{ $projects->count() }} Projects</strong>
            </div>

            <!-- Projects Tabular View Table -->
            <div class="data-table-container" style="padding: 0; border: 1px solid var(--border-color); border-radius: 8px; overflow: hidden; box-shadow: none;">
                <table class="data-table">
                    <thead style="background: {{ $org->color_code }};">
                        <tr>
                            <th style="width: 28%; color: #ffffff; background: transparent; font-weight: 800;">Project Name & Abbr</th>
                            <th style="width: 32%; color: #ffffff; background: transparent; font-weight: 800;">Description</th>
                            <th style="width: 18%; color: #ffffff; background: transparent; font-weight: 800;">Timeline</th>
                            <th style="width: 10%; color: #ffffff; background: transparent; font-weight: 800;">Tasks</th>
                            <th style="width: 12%; color: #ffffff; background: transparent; font-weight: 800; text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($projects as $p)
                            <tr>
                                <td>
                                    <strong style="font-size: 0.94rem;">
                                        <a href="{{ route('projects.show', $p) }}" style="color: var(--text-main); text-decoration: none;">
                                            {{ $p->name }}
                                        </a>
                                    </strong>
                                    <div style="font-size: 0.76rem; font-weight: 700; color: var(--primary); margin-top: 0.1rem;">
                                        {{ $p->abbreviation }}-#
                                    </div>
                                </td>
                                <td>
                                    <span style="font-size: 0.82rem; color: var(--text-muted);">
                                        {{ Str::limit($p->description, 85, '...') }}
                                    </span>
                                </td>
                                <td>
                                    <div style="font-size: 0.82rem; color: var(--text-main); font-weight: 600;">
                                        {{ $p->start_date ? $p->start_date->format('M d, Y') : 'TBD' }} &rarr; {{ $p->due_date ? $p->due_date->format('M d, Y') : 'TBD' }}
                                    </div>
                                </td>
                                <td>
                                    <strong style="font-size: 0.85rem; color: var(--text-main);">
                                        {{ $p->tasks_count }} Tasks
                                    </strong>
                                </td>
                                <td style="text-align: right;">
                                    <div style="display: inline-flex; gap: 0.4rem; justify-content: flex-end; align-items: center;">
                                        <a href="{{ route('projects.show', $p) }}" class="btn btn-secondary" style="font-size: 0.75rem; padding: 0.25rem 0.6rem;">
                                            Details
                                        </a>
                                        <a href="{{ route('projects.kanban', $p) }}" class="btn btn-primary" style="font-size: 0.75rem; padding: 0.25rem 0.6rem;">
                                            Kanban &rarr;
                                        </a>
                                        @can('delete', $p)
                                            <form action="{{ route('projects.destroy', $p) }}" method="POST" onsubmit="return promptDelete('{{ addslashes($p->name) }} Project', this);" style="display:inline; margin:0;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;">Delete</button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @empty
        <div style="background: var(--bg-surface); border: 1px dashed var(--border-color); padding: 3rem; text-align: center; border-radius: 12px;">
            <p style="color: var(--text-muted);">No projects found within your access scope.</p>
        </div>
    @endforelse
</div>

<!-- Create Project Modal -->
@if($canCreateProjects)
<div id="createProjectModal" style="display: none; position: fixed; top:0; left:0; right:0; bottom:0; background: rgba(0,0,0,0.6); align-items:center; justify-content:center; z-index:900; backdrop-filter: blur(4px);">
    <div style="background: var(--bg-surface); padding: 2rem; border-radius: 14px; width: 90%; max-width: 520px; border: 1px solid var(--border-color);">
        <h3 style="margin-bottom: 1rem; font-weight: 800; color: var(--primary);">Create New Project</h3>
        <form action="{{ route('projects.store') }}" method="POST">
            @csrf
            <div style="margin-bottom: 1rem;">
                <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Organization</label>
                <select name="organization_id" required style="width:100%; padding:0.6rem; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main);">
                    @foreach($organizations as $o)
                        <option value="{{ $o->id }}">{{ $o->name }}</option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Project Name</label>
                <input type="text" name="name" placeholder="e.g. PragmaTick Core Engine" required style="width:100%; padding:0.6rem; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main);">
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Abbreviation Key (Prefix)</label>
                <input type="text" name="abbreviation" placeholder="e.g. PRAG" required maxlength="10" style="width:100%; padding:0.6rem; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main); font-family: monospace;">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Start Date</label>
                    <input type="date" name="start_date" style="width:100%; padding:0.6rem; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main);">
                </div>
                <div>
                    <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">End / Due Date</label>
                    <input type="date" name="due_date" style="width:100%; padding:0.6rem; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main);">
                </div>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Description</label>
                <textarea name="description" rows="3" style="width:100%; padding:0.6rem; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main);"></textarea>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:0.75rem;">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('createProjectModal').style.display='none'">Cancel</button>
                <button type="submit" class="btn btn-primary">Create Project</button>
            </div>
        </form>
    </div>
</div>
@endif
@endsection
