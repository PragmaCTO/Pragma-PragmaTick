@extends('layouts.app')

@section('title', $project->name . ' - PragmaTick')

@section('content')
<div style="margin-bottom: 2rem;">
    <a href="{{ route('projects.index') }}" style="color: var(--primary); text-decoration: none; font-weight: 600; font-size: 0.88rem;">&larr; Back to Projects</a>
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 0.5rem; flex-wrap: wrap; gap: 1rem;">
        <div>
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <h1 style="font-size: 1.85rem; font-weight: 800;">{{ $project->name }}</h1>
                <span class="tag tag-green">Prefix: {{ $project->abbreviation }}-#</span>
            </div>
            <p style="color: var(--text-muted); font-size: 0.95rem; margin-top: 0.3rem;">
                Organization: <a href="{{ route('organizations.show', $project->organization) }}" style="color: var(--primary); font-weight: 600; text-decoration: none;">{{ $project->organization->name }}</a>
                <span style="margin: 0 0.5rem; opacity: 0.5;">|</span>
                Timeline: <strong style="color: var(--primary);">{{ $project->start_date ? $project->start_date->format('M d, Y') : 'TBD' }} &rarr; {{ $project->due_date ? $project->due_date->format('M d, Y') : 'TBD' }}</strong>
            </p>
        </div>

        <!-- Consolidated Action Dropdown & Primary Kanban Button -->
        <div style="display: flex; gap: 0.75rem; align-items: center;">
            @can('update', $project)
                <button class="btn btn-secondary" onclick="document.getElementById('editProjectModal').style.display='flex'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                    <span>Edit Project</span>
                </button>
            @endcan

            <a href="{{ route('projects.kanban', $project) }}" class="btn btn-primary">
                <span>Open Kanban Engine</span>
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
            </a>

            <div class="dropdown">
                <button class="btn btn-secondary" onclick="toggleDropdown('projectActionsMenu')">
                    <span>Actions</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="m6 9 6 6 6-6"/></svg>
                </button>
                <div class="dropdown-menu" id="projectActionsMenu">
                    <a href="{{ route('wikis.index') }}" class="dropdown-item">Project Documentation</a>
                    @can('delete', $project)
                        <form action="{{ route('projects.destroy', $project) }}" method="POST" onsubmit="return promptDelete('{{ addslashes($project->name) }} Project', this);" style="margin:0;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="dropdown-item" style="color: var(--accent-rose);">Soft Delete Project</button>
                        </form>
                    @endcan
                </div>
            </div>
        </div>
    </div>
    <p style="color: var(--text-muted); font-size: 0.95rem; margin-top: 0.5rem;">{{ $project->description }}</p>

    <div style="margin-top: 2rem; background: var(--bg-surface); padding: 1.5rem; border-radius: 12px; border: 1px solid var(--border-color);">
        <h3 style="font-weight: 800; color: var(--primary); margin-bottom: 1rem;">Project Discussion</h3>
        <div style="max-height: 300px; overflow-y: auto; margin-bottom: 1rem; display: flex; flex-direction: column; gap: 0.75rem;">
            @forelse($project->comments as $comment)
                <div style="background: var(--bg-surface-elevated); padding: 0.75rem; border-radius: 8px; border: 1px solid var(--border-color); font-size: 0.85rem;">
                    <div style="display:flex; justify-content:space-between; margin-bottom: 0.2rem;">
                        <strong style="color: var(--primary);">{{ $comment->user->name ?? 'Unknown' }}</strong>
                        <span style="color: var(--text-muted); font-size: 0.75rem;">{{ $comment->created_at->format('M d, Y H:i') }}</span>
                    </div>
                    <div style="color: var(--text-main);">{{ $comment->content }}</div>
                </div>
            @empty
                <div style="color: var(--text-muted); font-size: 0.85rem;">No comments yet.</div>
            @endforelse
        </div>
        <form action="{{ route('comments.store', ['type' => 'project', 'id' => $project->id]) }}" method="POST">
            @csrf
            <div style="display: flex; gap: 0.5rem;">
                <input type="text" name="content" required placeholder="Add a comment..." style="flex: 1; padding:0.6rem; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main);">
                <button type="submit" class="btn btn-primary">Post</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Project Modal -->
@can('update', $project)
<div id="editProjectModal" style="display: none; position: fixed; top:0; left:0; right:0; bottom:0; background: rgba(0,0,0,0.6); align-items:center; justify-content:center; z-index:900; backdrop-filter: blur(4px);">
    <div style="background: var(--bg-surface); padding: 2rem; border-radius: 14px; width: 90%; max-width: 500px; border: 1px solid var(--border-color);">
        <h3 style="margin-bottom: 1rem; font-weight: 800; color: var(--primary);">Edit Project Details</h3>
        <form action="{{ route('projects.update', $project) }}" method="POST" id="editProjectForm">
            @csrf
            @method('PUT')
            
            <div style="margin-bottom: 1rem;">
                <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Project Name</label>
                <input type="text" name="name" value="{{ $project->name }}" required style="width:100%; padding:0.6rem; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main);">
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Abbreviation Code (Prefix)</label>
                <input type="text" name="abbreviation" value="{{ $project->abbreviation }}" required style="width:100%; padding:0.6rem; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main);">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Start Date</label>
                    <input type="date" name="start_date" id="edit_project_start_date" value="{{ $project->start_date ? $project->start_date->format('Y-m-d') : '' }}" style="width:100%; padding:0.6rem; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main);">
                </div>
                <div>
                    <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Due Date</label>
                    <input type="date" name="due_date" id="edit_project_due_date" value="{{ $project->due_date ? $project->due_date->format('Y-m-d') : '' }}" style="width:100%; padding:0.6rem; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main);">
                </div>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Description</label>
                <textarea name="description" rows="3" style="width:100%; padding:0.6rem; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main);">{{ $project->description }}</textarea>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:0.75rem;">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('editProjectModal').style.display='none'">Cancel</button>
                <button type="submit" class="btn btn-primary">Update Project</button>
            </div>
        </form>
    </div>
</div>
@endcan

<!-- 2-Column Responsive Workspace Grid (Left 70%: Tasks & Milestones | Right 30%: Team Roster) -->
<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; flex-wrap: wrap;">
    
    <!-- Left Column: Tasks & Milestones -->
    <div>
        
        <!-- High-Density Project Tasks Section -->
        <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 12px; padding: 1.5rem; box-shadow: var(--card-shadow); margin-bottom: 1.5rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.6rem;">
                <h3 style="font-size: 1.15rem; font-weight: 700;">Project Tasks</h3>
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <strong style="font-size: 0.88rem; font-weight: 700; color: var(--primary);">{{ $project->tasks_count ?? $project->tasks->count() }} Tasks</strong>
                    <a href="{{ route('projects.kanban', $project) }}" class="btn btn-secondary" style="font-size: 0.78rem; padding: 0.25rem 0.65rem;">
                        View Kanban Board &rarr;
                    </a>
                </div>
            </div>

            <!-- Badgeless High-Density Tasks Table -->
            <div class="data-table-container" style="padding: 0; border: none; box-shadow: none;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width: 12%;">Task Code</th>
                            <th style="width: 25%;">Title</th>
                            <th style="width: 10%;">Type</th>
                            <th style="width: 10%;">Priority</th>
                            <th style="width: 10%;">Status</th>
                            <th style="width: 10%;">Start Date</th>
                            <th style="width: 10%;">Due Date</th>
                            <th style="width: 13%; text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($project->tasks as $t)
                        <tr>
                            <td>
                                <strong style="font-weight: 700; color: var(--primary); font-size: 0.84rem;">{{ $t->code }}</strong>
                            </td>
                            <td>
                                <strong>{{ $t->title }}</strong>
                                @if($t->parent)
                                    <br><span style="font-size: 0.78rem; color: var(--text-muted);">Parent: {{ $t->parent->code }}</span>
                                @endif
                            </td>
                            <td>
                                <strong style="font-weight: 700; color: var(--text-main); font-size: 0.82rem; text-transform: capitalize;">{{ $t->type }}</strong>
                            </td>
                            <td>
                                <strong style="font-weight: 700; color: var(--text-main); font-size: 0.82rem; text-transform: capitalize;">{{ $t->priority }}</strong>
                            </td>
                            <td>
                                <strong style="font-weight: 700; color: var(--primary); font-size: 0.82rem;">{{ $t->status }}</strong>
                            </td>
                            <td>
                                <span style="font-size: 0.82rem; color: var(--text-main);">{{ $t->start_date ? $t->start_date->format('M d, Y') : 'TBD' }}</span>
                            </td>
                            <td>
                                <span style="font-size: 0.82rem; color: var(--text-main);">{{ $t->due_date ? $t->due_date->format('M d, Y') : 'TBD' }}</span>
                            </td>
                            <td style="text-align: right;">
                                <a href="{{ route('projects.kanban', [$project, 'tab' => 'table']) }}" class="btn btn-secondary" style="font-size: 0.72rem; padding: 0.15rem 0.45rem;">
                                    Edit Task &rarr;
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" style="padding: 2rem; text-align: center; color: var(--text-muted);">No tasks created in this project yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Milestones Section -->
        <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 12px; padding: 1.5rem; box-shadow: var(--card-shadow);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.6rem;">
                <h3 style="font-size: 1.15rem; font-weight: 700;">Project Milestones</h3>
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <strong style="font-size: 0.88rem; font-weight: 700; color: var(--primary);">{{ $project->milestones_count ?? $project->milestones->count() }} Milestones</strong>
                    @can('manage', [\App\Models\Milestone::class, $project])
                        <button class="btn btn-primary" style="font-size: 0.78rem; padding: 0.25rem 0.65rem;" onclick="document.getElementById('createMilestoneModal').style.display='flex'">
                            + Create Milestone
                        </button>
                    @endcan
                </div>
            </div>

            <div style="display: flex; flex-direction: column; gap: 1rem;">
                @forelse($project->milestones as $m)
                    <div style="background: var(--bg-surface-elevated); border: 1px solid var(--border-color); border-radius: 8px; padding: 1rem;">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                            <div>
                                <strong style="font-size: 0.98rem; color: var(--text-main);">{{ $m->title }}</strong>
                                <p style="font-size: 0.82rem; color: var(--text-muted); margin-top: 0.2rem;">{{ $m->description }}</p>
                            </div>
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <strong style="font-size: 0.8rem; font-weight: 700; color: var(--primary); text-transform: uppercase;">{{ str_replace('_', ' ', $m->status) }}</strong>
                                @can('manage', [\App\Models\Milestone::class, $project])
                                    <button class="btn btn-secondary" style="font-size: 0.72rem; padding: 0.15rem 0.45rem;" onclick="openEditMilestoneModal({{ json_encode($m) }})">
                                        Edit
                                    </button>
                                @endcan
                            </div>
                        </div>
                        <div style="font-size: 0.78rem; color: var(--text-muted); margin-top: 0.6rem; border-top: 1px solid var(--border-color); padding-top: 0.4rem;">
                            Timeline: {{ $m->start_date ? $m->start_date->format('Y-m-d') : 'TBD' }} to {{ $m->due_date ? $m->due_date->format('Y-m-d') : 'TBD' }}
                        </div>
                    </div>
                @empty
                    <p style="color: var(--text-muted); font-size: 0.88rem;">No milestones defined for this project.</p>
                @endforelse
            </div>
        </div>

    </div>

    <!-- Right Column: Project Roster & Meta -->
    <div>
        <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 12px; padding: 1.5rem; box-shadow: var(--card-shadow);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <h3 style="font-size: 1.1rem; font-weight: 700;">Assigned Team Roster</h3>
                <strong style="font-size: 0.88rem; font-weight: 700; color: var(--primary);">{{ $project->users_count ?? $project->users->count() }} Members</strong>
            </div>

            <!-- Add Existing User to Project Form -->
            @can('manageMembers', $project)
                <form action="{{ route('projects.addMember', $project) }}" method="POST" style="margin-bottom: 1.25rem; background: var(--bg-surface-elevated); padding: 1rem; border-radius: 8px; border: 1px solid var(--border-color);">
                    @csrf
                    <div style="font-size: 0.82rem; font-weight: 700; color: var(--primary); margin-bottom: 0.5rem;">+ Add User to Project</div>
                    
                    <select name="user_id" required style="width: 100%; padding: 0.45rem; border-radius: 6px; border: 1px solid var(--border-color); background: var(--bg-surface); color: var(--text-main); font-size: 0.82rem; margin-bottom: 0.5rem;">
                        @foreach($allUsers as $u)
                            <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                        @endforeach
                    </select>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem; margin-bottom: 0.5rem;">
                        <select name="role" required style="padding: 0.45rem; border-radius: 6px; border: 1px solid var(--border-color); background: var(--bg-surface); color: var(--text-main); font-size: 0.82rem;">
                            <option value="member">Member</option>
                            <option value="project_admin">Project Admin</option>
                        </select>

                        <input type="text" name="position" placeholder="Position / Role" style="padding: 0.45rem; border-radius: 6px; border: 1px solid var(--border-color); background: var(--bg-surface); color: var(--text-main); font-size: 0.82rem;">
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; font-size: 0.8rem;">Add Member</button>
                </form>
            @endcan

            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                @forelse($project->users as $u)
                    <div style="background: var(--bg-surface-elevated); padding: 0.65rem 0.85rem; border-radius: 8px; border: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <strong style="font-size: 0.88rem;">{{ $u->name }}</strong>
                            <div style="font-size: 0.78rem; color: var(--text-muted);">{{ $u->pivot->position ?: 'Project Member' }}</div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <strong style="font-size: 0.8rem; font-weight: 700; color: var(--primary); text-transform: uppercase;">{{ $u->pivot->role }}</strong>
                            @can('manageMembers', $project)
                                <form action="{{ route('projects.removeMember', [$project, $u]) }}" method="POST" onsubmit="return promptDelete('Remove member {{ addslashes($u->name) }} from project {{ addslashes($project->name) }}', this);" style="display:inline; margin:0;">
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

<!-- Create Milestone Modal -->
@can('manage', [\App\Models\Milestone::class, $project])
<div id="createMilestoneModal" class="modal-backdrop">
    <div class="modal-card" style="max-width: 480px;">
        <div class="modal-header">
            <h3 style="font-weight: 800; color: var(--primary);">Create New Milestone</h3>
            <button type="button" onclick="document.getElementById('createMilestoneModal').style.display='none'" style="background:none; border:none; color: var(--text-muted); cursor:pointer;">✕</button>
        </div>
        <form action="{{ route('milestones.store', $project) }}" method="POST" id="createMilestoneForm">
            @csrf
            <div class="modal-body">
                <div style="margin-bottom: 1rem;">
                    <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Milestone Title</label>
                    <input type="text" name="title" required placeholder="e.g. Sprint Alpha Release" style="width:100%;">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                    <div>
                        <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Start Date</label>
                        <input type="date" name="start_date" id="create_ms_start_date" style="width:100%;">
                    </div>
                    <div>
                        <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Due Date</label>
                        <input type="date" name="due_date" id="create_ms_due_date" style="width:100%;">
                    </div>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Initial Status</label>
                    <select name="status" required style="width:100%;">
                        <option value="open">Open</option>
                        <option value="in_progress">In Progress</option>
                        <option value="completed">Completed</option>
                        <option value="closed">Closed</option>
                    </select>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Description</label>
                    <textarea name="description" rows="3" style="width:100%;"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('createMilestoneModal').style.display='none'">Cancel</button>
                <button type="submit" class="btn btn-primary">Create Milestone</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Milestone Modal -->
<div id="editMilestoneModal" class="modal-backdrop" style="display: none;">
    <div class="modal-card" style="max-width: 480px;">
        <div class="modal-header">
            <h3 style="font-weight: 800; color: var(--primary);">Edit Milestone Details</h3>
            <button type="button" onclick="document.getElementById('editMilestoneModal').style.display='none'" style="background:none; border:none; color: var(--text-muted); cursor:pointer;">✕</button>
        </div>
        <form action="" method="POST" id="editMilestoneForm">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div style="margin-bottom: 1rem;">
                    <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Milestone Title</label>
                    <input type="text" name="title" id="edit_ms_title" required style="width:100%;">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                    <div>
                        <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Start Date</label>
                        <input type="date" name="start_date" id="edit_ms_start_date" style="width:100%;">
                    </div>
                    <div>
                        <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Due Date</label>
                        <input type="date" name="due_date" id="edit_ms_due_date" style="width:100%;">
                    </div>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Status</label>
                    <select name="status" id="edit_ms_status" required style="width:100%;">
                        <option value="open">Open</option>
                        <option value="in_progress">In Progress</option>
                        <option value="completed">Completed</option>
                        <option value="closed">Closed</option>
                    </select>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Description</label>
                    <textarea name="description" id="edit_ms_description" rows="3" style="width:100%;"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('editMilestoneModal').style.display='none'">Cancel</button>
                <button type="submit" class="btn btn-primary">Update Milestone</button>
            </div>
        </form>
    </div>
</div>

<script>
    function bindDatePair(startId, dueId, formId) {
        const startEl = document.getElementById(startId);
        const dueEl = document.getElementById(dueId);
        const formEl = formId ? document.getElementById(formId) : null;
        if (!startEl || !dueEl) return;

        startEl.addEventListener('change', function() {
            if (this.value) {
                dueEl.min = this.value;
                if (dueEl.value && dueEl.value < this.value) {
                    dueEl.value = this.value;
                }
            } else {
                dueEl.removeAttribute('min');
            }
        });

        dueEl.addEventListener('change', function() {
            if (this.value) {
                startEl.max = this.value;
                if (startEl.value && startEl.value > this.value) {
                    startEl.value = this.value;
                }
            } else {
                startEl.removeAttribute('max');
            }
        });

        if (formEl) {
            formEl.addEventListener('submit', function(e) {
                if (startEl.value && dueEl.value && startEl.value > dueEl.value) {
                    e.preventDefault();
                    alert('Validation Error: Due Date (End Date) must be on or after Start Date.');
                    dueEl.focus();
                    return false;
                }
            });
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        bindDatePair('edit_project_start_date', 'edit_project_due_date', 'editProjectForm');
        bindDatePair('create_ms_start_date', 'create_ms_due_date', 'createMilestoneForm');
        bindDatePair('edit_ms_start_date', 'edit_ms_due_date', 'editMilestoneForm');
    });

    function openEditMilestoneModal(m) {
        document.getElementById('editMilestoneForm').action = '/milestones/' + m.id;
        document.getElementById('edit_ms_title').value = m.title || '';
        
        const startVal = m.start_date ? m.start_date.substring(0, 10) : '';
        const dueVal = m.due_date ? m.due_date.substring(0, 10) : '';
        const editStart = document.getElementById('edit_ms_start_date');
        const editDue = document.getElementById('edit_ms_due_date');
        editStart.value = startVal;
        editDue.value = dueVal;
        if (startVal) editDue.min = startVal; else editDue.removeAttribute('min');
        if (dueVal) editStart.max = dueVal; else editStart.removeAttribute('max');

        document.getElementById('edit_ms_status').value = m.status || 'open';
        document.getElementById('edit_ms_description').value = m.description || '';
        document.getElementById('editMilestoneModal').style.display = 'flex';
    }
</script>
@endcan
@endsection
