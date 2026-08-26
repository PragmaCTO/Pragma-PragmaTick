@extends('layouts.app')

@section('title', $organization->name . ' - PragmaTick')

@section('content')
<div style="margin-bottom: 2rem;">
    <a href="{{ route('organizations.index') }}" style="color: var(--primary); text-decoration: none; font-weight: 600; font-size: 0.88rem;">&larr; Back to Organizations</a>
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 0.5rem; flex-wrap: wrap; gap: 1rem;">
        <div style="display: flex; align-items: center; gap: 0.75rem;">
            <div style="width: 20px; height: 20px; border-radius: 4px; background: {{ $organization->color_code }};"></div>
            <h1 style="font-size: 1.85rem; font-weight: 800;">{{ $organization->name }}</h1>
        </div>

        <div style="display: flex; gap: 0.75rem; align-items: center;">
            @can('update', $organization)
                <button class="btn btn-secondary" onclick="document.getElementById('editOrganizationModal').style.display='flex'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                    <span>Edit Organization</span>
                </button>
            @endcan

            <a href="{{ route('wikis.index') }}" class="btn btn-secondary">
                <span>Organization Documentation</span>
            </a>

            <div class="dropdown">
                <button class="btn btn-secondary" onclick="toggleDropdown('orgActionsMenu')">
                    <span>Actions</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="m6 9 6 6 6-6"/></svg>
                </button>
                <div class="dropdown-menu" id="orgActionsMenu">
                    @can('delete', $organization)
                        <form action="{{ route('organizations.destroy', $organization) }}" method="POST" onsubmit="return promptDelete('{{ addslashes($organization->name) }} Organization', this);" style="margin:0;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="dropdown-item" style="color: var(--accent-rose);">Soft Delete Organization</button>
                        </form>
                    @endcan
                </div>
            </div>
        </div>
    </div>
    <p style="color: var(--text-muted); font-size: 0.95rem; margin-top: 0.4rem;">{{ $organization->description }}</p>
</div>

<!-- Edit Organization Modal -->
@can('update', $organization)
<div id="editOrganizationModal" style="display: none; position: fixed; top:0; left:0; right:0; bottom:0; background: rgba(0,0,0,0.6); align-items:center; justify-content:center; z-index:900; backdrop-filter: blur(4px);">
    <div style="background: var(--bg-surface); padding: 2rem; border-radius: 14px; width: 90%; max-width: 480px; border: 1px solid var(--border-color);">
        <h3 style="margin-bottom: 1rem; font-weight: 800; color: var(--primary);">Edit Organization Details</h3>
        <form action="{{ route('organizations.update', $organization) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div style="margin-bottom: 1rem;">
                <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Organization Name</label>
                <input type="text" name="name" value="{{ $organization->name }}" required style="width:100%; padding:0.6rem; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main);">
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Theme Accent Color</label>
                <input type="color" name="color_code" value="{{ $organization->color_code }}" style="width:100%; height:40px; border-radius:6px; border:1px solid var(--border-color); cursor:pointer;">
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Description</label>
                <textarea name="description" rows="3" style="width:100%; padding:0.6rem; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main);">{{ $organization->description }}</textarea>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:0.75rem;">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('editOrganizationModal').style.display='none'">Cancel</button>
                <button type="submit" class="btn btn-primary">Update Organization</button>
            </div>
        </form>
    </div>
</div>
@endcan

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; flex-wrap: wrap;">
    
    <!-- Left Column: Assigned Projects (High-Density Tabular View) -->
    <div>
        <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 12px; padding: 1.5rem; box-shadow: var(--card-shadow); margin-bottom: 1.5rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; border-bottom: 2.5px solid {{ $organization->color_code }}; padding-bottom: 0.6rem;">
                <h3 style="font-size: 1.15rem; font-weight: 700;">Assigned Projects</h3>
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <span style="font-size: 0.88rem; font-weight: 700; color: var(--primary);">{{ $organization->projects_count ?? $organization->projects->count() }} Projects</span>
                    @if(auth()->user()->isSuperAdmin() || auth()->user()->isOrgAdmin($organization->id))
                        <button class="btn btn-primary" style="font-size: 0.75rem; padding: 0.25rem 0.6rem;" onclick="document.getElementById('createProjectModal').style.display='flex'">
                            + Create Project
                        </button>
                    @endif
                </div>
            </div>

            <div class="data-table-container" style="padding: 0; border: 1px solid var(--border-color); border-radius: 8px; overflow: hidden; box-shadow: none;">
                <table class="data-table">
                    <thead style="background: {{ $organization->color_code }};">
                        <tr>
                            <th style="width: 35%; color: #ffffff; background: transparent; font-weight: 800;">Project & Abbr</th>
                            <th style="width: 35%; color: #ffffff; background: transparent; font-weight: 800;">Description</th>
                            <th style="width: 15%; color: #ffffff; background: transparent; font-weight: 800;">Tasks</th>
                            <th style="width: 15%; color: #ffffff; background: transparent; font-weight: 800; text-align: right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($organization->projects as $p)
                            <tr>
                                <td>
                                    <strong style="font-size: 0.92rem;">
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
                                        {{ Str::limit($p->description, 75, '...') }}
                                    </span>
                                </td>
                                <td>
                                    <strong style="font-size: 0.84rem; color: var(--text-main);">
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
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align: center; padding: 2rem; color: var(--text-muted);">
                                    No projects in this organization.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Right Column: Organization Members Roster -->
    <div>
        <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 12px; padding: 1.5rem; box-shadow: var(--card-shadow);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <h3 style="font-size: 1.1rem; font-weight: 700;">Organization Members</h3>
                <span style="font-size: 0.88rem; font-weight: 700; color: var(--primary);">{{ $organization->users_count ?? $organization->users->count() }} Members</span>
            </div>

            <!-- Add Existing User Form -->
            @can('update', $organization)
                <form action="{{ route('organizations.addMember', $organization) }}" method="POST" style="margin-bottom: 1.25rem; background: var(--bg-surface-elevated); padding: 1rem; border-radius: 8px; border: 1px solid var(--border-color);">
                    @csrf
                    <div style="font-size: 0.82rem; font-weight: 700; color: var(--primary); margin-bottom: 0.5rem;">+ Add Existing User to Org</div>
                    
                    <select name="user_id" required style="width: 100%; padding: 0.45rem; border-radius: 6px; border: 1px solid var(--border-color); background: var(--bg-surface); color: var(--text-main); font-size: 0.82rem; margin-bottom: 0.5rem;">
                        @foreach($allUsers as $u)
                            <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                        @endforeach
                    </select>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem; margin-bottom: 0.5rem;">
                        <select name="role" required style="padding: 0.45rem; border-radius: 6px; border: 1px solid var(--border-color); background: var(--bg-surface); color: var(--text-main); font-size: 0.82rem;">
                            <option value="member">Member</option>
                            <option value="org_admin">Org Admin</option>
                        </select>

                        <input type="text" name="position" placeholder="Position / Title" style="padding: 0.45rem; border-radius: 6px; border: 1px solid var(--border-color); background: var(--bg-surface); color: var(--text-main); font-size: 0.82rem;">
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; font-size: 0.8rem;">Add Member</button>
                </form>
            @endcan

            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                @forelse($organization->users as $u)
                    <div style="background: var(--bg-surface-elevated); padding: 0.65rem 0.85rem; border-radius: 8px; border: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <strong style="font-size: 0.88rem;">{{ $u->name }}</strong>
                            <div style="font-size: 0.78rem; color: var(--text-muted);">{{ $u->pivot->position ?: 'Org Member' }}</div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <strong style="font-size: 0.8rem; font-weight: 700; color: var(--primary); text-transform: uppercase;">{{ $u->pivot->role }}</strong>
                            @can('update', $organization)
                                <form action="{{ route('organizations.removeMember', [$organization, $u]) }}" method="POST" onsubmit="return promptDelete('Remove member {{ addslashes($u->name) }} from {{ addslashes($organization->name) }}', this);" style="display:inline; margin:0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" style="font-size: 0.7rem; padding: 0.15rem 0.4rem;">
                                        Remove
                                    </button>
                                </form>
                            @endcan
                        </div>
                    </div>
                @empty
                    <p style="color: var(--text-muted); font-size: 0.85rem;">No explicit members assigned.</p>
                @endforelse
            </div>
        </div>
    </div>

</div>

<!-- Create Project Modal -->
@if(auth()->user()->isSuperAdmin() || auth()->user()->isOrgAdmin($organization->id))
<div id="createProjectModal" style="display: none; position: fixed; top:0; left:0; right:0; bottom:0; background: rgba(0,0,0,0.6); align-items:center; justify-content:center; z-index:900; backdrop-filter: blur(4px);">
    <div style="background: var(--bg-surface); padding: 2rem; border-radius: 14px; width: 90%; max-width: 500px; border: 1px solid var(--border-color);">
        <h3 style="margin-bottom: 1rem; font-weight: 800; color: var(--primary);">+ Create New Project</h3>
        
        <form action="{{ route('projects.store') }}" method="POST">
            @csrf
            <input type="hidden" name="organization_id" value="{{ $organization->id }}">
            
            <div style="margin-bottom: 1rem;">
                <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Project Name</label>
                <input type="text" name="name" required placeholder="e.g. Website Redesign" style="width:100%; padding:0.6rem; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main);">
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Abbreviation Code (Prefix)</label>
                <input type="text" name="abbreviation" required placeholder="e.g. WEB" style="width:100%; padding:0.6rem; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main);">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Start Date</label>
                    <input type="date" name="start_date" style="width:100%; padding:0.6rem; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main);">
                </div>
                <div>
                    <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Due Date</label>
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
