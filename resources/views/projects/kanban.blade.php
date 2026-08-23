@extends('layouts.app')

@section('title', $project->name . ' - Kanban & Task Engine')

@section('content')
<!-- Select2 & jQuery CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style>
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

    .kanban-board {
        display: flex;
        gap: 1.25rem;
        overflow-x: auto;
        padding-bottom: 1.5rem;
        min-height: 600px;
    }

    .kanban-column {
        flex: 0 0 310px;
        background: var(--bg-surface-elevated);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        display: flex;
        flex-direction: column;
        max-height: 75vh;
    }

    .kanban-column-header {
        padding: 1rem;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: var(--bg-surface);
        border-top-left-radius: 11px;
        border-top-right-radius: 11px;
    }

    .kanban-cards-container {
        padding: 0.85rem;
        display: flex;
        flex-direction: column;
        gap: 0.85rem;
        overflow-y: auto;
        flex: 1;
    }

    .kanban-card {
        background: var(--bg-surface);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        padding: 1rem;
        box-shadow: var(--card-shadow);
        cursor: grab;
        transition: transform 0.15s ease, border-color 0.15s ease;
    }

    .kanban-card:active {
        cursor: grabbing;
        transform: scale(0.98);
    }
</style>

<!-- Top Bar: Title, Segmented View Controls, and Consolidated Action Dropdown -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem; background: var(--bg-surface); padding: 1.25rem; border-radius: 12px; border: 1px solid var(--border-color);">
    <div>
        <div style="display: flex; align-items: center; gap: 0.75rem;">
            <h1 style="font-size: 1.65rem; font-weight: 800;">{{ $project->name }}</h1>
            <span class="tag tag-green">{{ $project->abbreviation }}-#</span>
        </div>
        <p style="color: var(--text-muted); font-size: 0.88rem; margin-top: 0.2rem;">
            Organization: <a href="{{ route('organizations.show', $project->organization) }}" style="color: var(--primary); text-decoration: none; font-weight: 600;">{{ $project->organization->name }}</a>
        </p>
    </div>

    <div style="display: flex; gap: 1rem; align-items: center;">
        <!-- Segmented View Toggle -->
        <div style="background: var(--bg-surface-elevated); padding: 0.2rem; border-radius: 8px; border: 1px solid var(--border-color); display: flex; gap: 0.2rem;">
            <button class="btn {{ $activeTab === 'kanban' ? 'btn-primary' : 'btn-secondary' }}" onclick="switchTab('kanban')" style="font-size: 0.8rem; padding: 0.35rem 0.75rem;">
                <span>Kanban Board</span>
            </button>
            <button class="btn {{ $activeTab === 'table' ? 'btn-primary' : 'btn-secondary' }}" onclick="switchTab('table')" style="font-size: 0.8rem; padding: 0.35rem 0.75rem;">
                <span>Tabular View</span>
            </button>
        </div>

        <div style="display: flex; gap: 0.5rem; align-items: center;">
            @can('create', [\App\Models\Milestone::class, $project])
                <button class="btn btn-secondary" style="font-size: 0.8rem; padding: 0.35rem 0.75rem;" onclick="document.getElementById('createMilestoneModal').style.display='flex'">+ Milestone</button>
            @endcan
            @can('create', [\App\Models\Task::class, $project])
                <button class="btn btn-primary" style="font-size: 0.8rem; padding: 0.35rem 0.75rem;" onclick="document.getElementById('createTaskModal').style.display='flex'">+ Task</button>
            @endcan
        </div>

        <!-- Consolidated Action Dropdown Menu -->
        <div class="dropdown">
            <button class="btn btn-secondary" style="font-size: 0.8rem; padding: 0.35rem 0.75rem;" onclick="toggleDropdown('kanbanActionsMenu')">
                <span>More Actions</span>
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="m6 9 6 6 6-6"/></svg>
            </button>
            <div class="dropdown-menu" id="kanbanActionsMenu">
                @can('update', $project)
                    <button class="dropdown-item" onclick="document.getElementById('manageStatusesModal').style.display='flex'">Configure Kanban Columns</button>
                @endcan
                <a href="{{ route('projects.show', $project) }}" class="dropdown-item">Project Overview</a>
            </div>
        </div>
    </div>
</div>

<!-- View Mode 1: Kanban Board View -->
<div id="kanbanViewSection" style="display: {{ $activeTab === 'kanban' ? 'block' : 'none' }};">
    <div class="kanban-board">
        @foreach($statuses as $status)
            @php
                $colTasks = $allTasks->filter(function($t) use ($status) {
                    $taskStatus = strtolower(trim((string)$t->status));
                    $statusName = strtolower(trim((string)$status->name));
                    $statusSlug = strtolower(trim((string)$status->slug));
                    return $taskStatus === $statusName || $taskStatus === $statusSlug || (string)$t->status_id === (string)$status->id;
                });
            @endphp
            
            <div class="kanban-column" data-status="{{ $status->name }}" ondragover="allowDrop(event)" ondrop="dropTask(event, '{{ $status->name }}')">
                <div class="kanban-column-header">
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <div style="width: 10px; height: 10px; border-radius: 50%; background: {{ $status->color }};"></div>
                        <strong style="font-size: 0.92rem; color: var(--text-main);">{{ $status->name }}</strong>
                    </div>

                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <span class="tag tag-cyan">{{ $colTasks->count() }}</span>
                        
                        @if(!$status->is_mandatory && auth()->user()->can('update', $project))
                            <form action="{{ route('statuses.destroy', $status) }}" method="POST" onsubmit="return confirm('Delete column {{ addslashes($status->name) }}?');" style="margin:0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background:none; border:none; color: var(--text-muted); cursor:pointer; font-size: 0.75rem;">✕</button>
                            </form>
                        @endif
                    </div>
                </div>

                <div class="kanban-cards-container">
                    @forelse($colTasks as $task)
                        <div class="kanban-card" id="task-card-{{ $task->id }}" draggable="true" ondragstart="dragTask(event, {{ $task->id }})">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.4rem;">
                                <span class="tag tag-green">{{ $task->code }}</span>
                                <span class="tag tag-amber" style="font-size: 0.65rem; text-transform: uppercase;">{{ $task->priority }}</span>
                            </div>

                            <strong style="font-size: 0.92rem; color: var(--text-main); display: block; margin-bottom: 0.4rem;">{{ $task->title }}</strong>
                            
                            @if($task->description)
                                <p style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 0.6rem;">{{ Str::limit($task->description, 70) }}</p>
                            @endif

                            @if($task->parent)
                                <div style="font-size: 0.75rem; color: var(--primary); margin-bottom: 0.5rem;">Parent: {{ $task->parent->code }}</div>
                            @endif

                            <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--border-color); padding-top: 0.5rem; margin-top: 0.5rem; font-size: 0.75rem; color: var(--text-muted);">
                                <span>Start: {{ $task->start_date ? $task->start_date->format('M d') : 'TBD' }}</span>
                                <span>Due: {{ $task->due_date ? $task->due_date->format('M d') : 'TBD' }}</span>
                            </div>

                            <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 0.5rem; margin-top: 0.2rem; font-size: 0.75rem; color: var(--text-muted);">
                                <span>Assignees: {{ $task->assignees->count() }}</span>
                                <div style="display: flex; gap: 0.35rem;">
                                    <button class="btn btn-secondary" style="font-size: 0.7rem; padding: 0.15rem 0.4rem;" onclick="openShowTaskModal({{ json_encode($task->load(['assignees', 'comments.user'])) }})">Details</button>
                                    @can('update', $task)
                                        <button class="btn btn-secondary" style="font-size: 0.7rem; padding: 0.15rem 0.4rem;" onclick="openEditTaskModal({{ json_encode($task->load(['assignees', 'comments.user'])) }})">Edit</button>
                                    @endcan
                                    @can('delete', $task)
                                        <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return promptDelete('Task {{ addslashes($task->code) }}', this);" style="margin:0;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" style="font-size: 0.7rem; padding: 0.15rem 0.4rem;">Delete</button>
                                        </form>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    @empty
                        <div style="text-align: center; color: var(--text-muted); font-size: 0.8rem; padding: 2rem 0; border: 1px dashed var(--border-color); border-radius: 8px;">
                            No tasks
                        </div>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- View Mode 2: Tabular View -->
<div id="tableViewSection" style="display: {{ $activeTab === 'table' ? 'block' : 'none' }};">
    <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 12px; padding: 1.5rem; box-shadow: var(--card-shadow);">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 0.88rem;">
                <thead>
                    <tr style="border-bottom: 1px solid var(--border-color); background: var(--bg-surface-elevated);">
                        <th style="padding: 0.75rem; text-align: left;">Task Code</th>
                        <th style="padding: 0.75rem; text-align: left;">Title</th>
                        <th style="padding: 0.75rem; text-align: left;">Type</th>
                        <th style="padding: 0.75rem; text-align: left;">Priority</th>
                        <th style="padding: 0.75rem; text-align: left;">Status</th>
                        <th style="padding: 0.75rem; text-align: left;">Start Date</th>
                        <th style="padding: 0.75rem; text-align: left;">Due Date</th>
                        <th style="padding: 0.75rem; text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($allTasks as $t)
                        <tr style="border-bottom: 1px solid var(--border-color);">
                            <td style="padding: 0.75rem;">
                                <strong style="font-weight: 700; color: var(--primary); font-size: 0.84rem;">{{ $t->code }}</strong>
                            </td>
                            <td style="padding: 0.75rem;">
                                <strong style="color: var(--text-main);">{{ $t->title }}</strong>
                                @if($t->parent)
                                    <br><span style="font-size: 0.78rem; color: var(--text-muted);">Parent: {{ $t->parent->code }}</span>
                                @endif
                            </td>
                            <td style="padding: 0.75rem;">
                                <strong style="font-weight: 700; color: var(--text-main); font-size: 0.82rem; text-transform: capitalize;">{{ $t->type }}</strong>
                            </td>
                            <td style="padding: 0.75rem;">
                                <strong style="font-weight: 700; color: var(--text-main); font-size: 0.82rem; text-transform: capitalize;">{{ $t->priority }}</strong>
                            </td>
                            <td style="padding: 0.75rem;">
                                <strong style="font-weight: 700; color: var(--primary); font-size: 0.82rem;">{{ $t->status }}</strong>
                            </td>
                            <td style="padding: 0.75rem;">
                                <span style="font-size: 0.82rem; color: var(--text-main);">{{ $t->start_date ? $t->start_date->format('M d, Y') : 'TBD' }}</span>
                            </td>
                            <td style="padding: 0.75rem;">
                                <span style="font-size: 0.82rem; color: var(--text-main);">{{ $t->due_date ? $t->due_date->format('M d, Y') : 'TBD' }}</span>
                            </td>
                            <td style="padding: 0.75rem; text-align: right;">
                                <button class="btn btn-secondary" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;" onclick="openShowTaskModal({{ json_encode($t->load(['assignees', 'comments.user'])) }})">Details</button>
                                @can('update', $t)
                                    <button class="btn btn-secondary" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;" onclick="openEditTaskModal({{ json_encode($t->load(['assignees', 'comments.user'])) }})">Edit</button>
                                @endcan
                                @can('delete', $t)
                                    <form action="{{ route('tasks.destroy', $t) }}" method="POST" onsubmit="return promptDelete('Task {{ addslashes($t->code) }}', this);" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;">Delete</button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="padding: 2rem; text-align: center; color: var(--text-muted);">No tasks found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal 1: Create Task Modal -->
<div id="createTaskModal" style="display: none; position: fixed; top:0; left:0; right:0; bottom:0; background: rgba(0,0,0,0.6); align-items:center; justify-content:center; z-index:900; backdrop-filter: blur(4px);">
    <div style="background: var(--bg-surface); padding: 2rem; border-radius: 14px; width: 90%; max-width: 520px; border: 1px solid var(--border-color);">
        <h3 style="margin-bottom: 1rem; font-weight: 800; color: var(--primary);">+ Create New Task</h3>
        <form action="{{ route('tasks.store', $project) }}" method="POST">
            @csrf
            <div style="margin-bottom: 1rem;">
                <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Task Title</label>
                <input type="text" name="title" required placeholder="e.g. Implement OAuth Token Handler" style="width:100%; padding:0.6rem; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main);">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Task Type</label>
                    <select name="type" required style="width:100%; padding:0.6rem; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main);">
                        <option value="feature">Feature</option>
                        <option value="bug">Bug</option>
                        <option value="documentation">Documentation</option>
                        <option value="operation">Operation</option>
                    </select>
                </div>
                <div>
                    <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Priority</label>
                    <select name="priority" required style="width:100%; padding:0.6rem; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main);">
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                        <option value="urgent">Urgent</option>
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Initial Status</label>
                    <select name="status" required style="width:100%; padding:0.6rem; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main);">
                        @foreach($statuses as $st)
                            <option value="{{ $st->name }}">{{ $st->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Milestone (Optional)</label>
                    <select name="milestone_id" style="width:100%; padding:0.6rem; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main);">
                        <option value="">Unassigned</option>
                        @foreach($project->milestones as $ms)
                            <option value="{{ $ms->id }}">{{ $ms->title }}</option>
                        @endforeach
                    </select>
                </div>
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

            <div style="margin-bottom: 1rem;">
                <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Parent Task Dependency (Optional)</label>
                <select name="parent_id" style="width:100%; padding:0.6rem; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main);">
                    <option value="">None (Top-Level Task)</option>
                    @foreach($allTasks as $parentOpt)
                        <option value="{{ $parentOpt->id }}">{{ $parentOpt->code }} - {{ $parentOpt->title }}</option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Assignees</label>
                <select name="assignees[]" class="select2-assignees" multiple style="width:100%;">
                    @foreach($project->users as $pu)
                        <option value="{{ $pu->id }}">{{ $pu->name }}</option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Description</label>
                <textarea name="description" rows="3" style="width:100%; padding:0.6rem; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main);"></textarea>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:0.75rem;">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('createTaskModal').style.display='none'">Cancel</button>
                <button type="submit" class="btn btn-primary">Create Task</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal 2: Edit Task Modal -->
<div id="editTaskModal" style="display: none; position: fixed; top:0; left:0; right:0; bottom:0; background: rgba(0,0,0,0.6); align-items:center; justify-content:center; z-index:900; backdrop-filter: blur(4px);">
    <div style="background: var(--bg-surface); padding: 2rem; border-radius: 14px; width: 90%; max-width: 540px; border: 1px solid var(--border-color); max-height: 85vh; overflow-y: auto;">
        <h3 style="margin-bottom: 1rem; font-weight: 800; color: var(--primary);">Edit Task & Discussion Thread</h3>
        <form action="" method="POST" id="editTaskForm">
            @csrf
            @method('PUT')
            
            <div style="margin-bottom: 1rem;">
                <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Title</label>
                <input type="text" name="title" id="edit_task_title" required style="width:100%; padding:0.6rem; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main);">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Type</label>
                    <select name="type" id="edit_task_type" required style="width:100%; padding:0.6rem; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main);">
                        <option value="feature">Feature</option>
                        <option value="bug">Bug</option>
                        <option value="documentation">Documentation</option>
                        <option value="operation">Operation</option>
                    </select>
                </div>
                <div>
                    <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Priority</label>
                    <select name="priority" id="edit_task_priority" required style="width:100%; padding:0.6rem; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main);">
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                        <option value="urgent">Urgent</option>
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Status</label>
                    <select name="status" id="edit_task_status" required style="width:100%; padding:0.6rem; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main);">
                        @foreach($statuses as $st)
                            <option value="{{ $st->name }}">{{ $st->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Start Date</label>
                    <input type="date" name="start_date" id="edit_task_start_date" style="width:100%; padding:0.6rem; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main);">
                </div>
                <div>
                    <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Due Date</label>
                    <input type="date" name="due_date" id="edit_task_due_date" style="width:100%; padding:0.6rem; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main);">
                </div>
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Assignees</label>
                <select name="assignees[]" id="edit_task_assignees" class="select2-edit-assignees" multiple style="width:100%;">
                    @foreach($project->users as $pu)
                        <option value="{{ $pu->id }}">{{ $pu->name }}</option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Description</label>
                <textarea name="description" id="edit_task_description" rows="3" style="width:100%; padding:0.6rem; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main);"></textarea>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:0.75rem;">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('editTaskModal').style.display='none'">Cancel</button>
                <button type="submit" class="btn btn-primary">Update Task</button>
            </div>
        </form>

    </div>
</div>

<!-- Modal: Show Task Modal -->
<div id="showTaskModal" style="display: none; position: fixed; top:0; left:0; right:0; bottom:0; background: rgba(0,0,0,0.6); align-items:center; justify-content:center; z-index:900; backdrop-filter: blur(4px);">
    <div style="background: var(--bg-surface); padding: 2rem; border-radius: 14px; width: 90%; max-width: 600px; border: 1px solid var(--border-color); max-height: 85vh; overflow-y: auto;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
            <h3 style="font-weight: 800; color: var(--primary);" id="show_task_title">Task Details</h3>
            <button class="btn btn-secondary" onclick="document.getElementById('showTaskModal').style.display='none'">✕</button>
        </div>
        
        <div style="background: var(--bg-surface-elevated); padding: 1rem; border-radius: 8px; border: 1px solid var(--border-color); margin-bottom: 1.5rem;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; font-size: 0.85rem; margin-bottom: 0.75rem;">
                <div><strong style="color: var(--text-muted);">Status:</strong> <span id="show_task_status" style="font-weight: 700;"></span></div>
                <div><strong style="color: var(--text-muted);">Priority:</strong> <span id="show_task_priority" style="font-weight: 700; text-transform: capitalize;"></span></div>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; font-size: 0.85rem; margin-bottom: 0.75rem;">
                <div><strong style="color: var(--text-muted);">Start Date:</strong> <span id="show_task_start_date"></span></div>
                <div><strong style="color: var(--text-muted);">Due Date:</strong> <span id="show_task_due_date"></span></div>
            </div>
            <div style="font-size: 0.85rem;">
                <strong style="color: var(--text-muted); display: block; margin-bottom: 0.2rem;">Description:</strong>
                <div id="show_task_description" style="color: var(--text-main); white-space: pre-wrap;"></div>
            </div>
        </div>

        <hr style="border: 0; border-top: 1px solid var(--border-color); margin: 1.5rem 0;">
        
        <h4 style="font-weight: 700; color: var(--primary); margin-bottom: 1rem; font-size: 0.95rem;">Discussion Thread</h4>
        <div id="taskCommentsList" style="max-height: 250px; overflow-y: auto; margin-bottom: 1rem; display: flex; flex-direction: column; gap: 0.75rem; padding-right: 0.5rem;">
            <!-- Comments injected via JS -->
        </div>
        
        <form action="" method="POST" id="taskCommentForm">
            @csrf
            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                <textarea name="content" required placeholder="Add a comment... (Markdown allowed)" rows="3" style="width: 100%; padding:0.6rem; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main);"></textarea>
                <div style="display: flex; justify-content: flex-end;">
                    <button type="submit" class="btn btn-primary">Post Comment</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal 3: Create Milestone Modal -->
<div id="createMilestoneModal" style="display: none; position: fixed; top:0; left:0; right:0; bottom:0; background: rgba(0,0,0,0.6); align-items:center; justify-content:center; z-index:900; backdrop-filter: blur(4px);">
    <div style="background: var(--bg-surface); padding: 2rem; border-radius: 14px; width: 90%; max-width: 480px; border: 1px solid var(--border-color);">
        <h3 style="margin-bottom: 1rem; font-weight: 800; color: var(--primary);">+ Create New Milestone</h3>
        <form action="{{ route('milestones.store', $project) }}" method="POST">
            @csrf
            <div style="margin-bottom: 1rem;">
                <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Milestone Title</label>
                <input type="text" name="title" required placeholder="e.g. Sprint Alpha Release" style="width:100%; padding:0.6rem; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main);">
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
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('createMilestoneModal').style.display='none'">Cancel</button>
                <button type="submit" class="btn btn-primary">Create Milestone</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal 4: Manage Status Columns Modal -->
<div id="manageStatusesModal" style="display: none; position: fixed; top:0; left:0; right:0; bottom:0; background: rgba(0,0,0,0.6); align-items:center; justify-content:center; z-index:900; backdrop-filter: blur(4px);">
    <div style="background: var(--bg-surface); padding: 2rem; border-radius: 14px; width: 90%; max-width: 480px; border: 1px solid var(--border-color);">
        <h3 style="margin-bottom: 1rem; font-weight: 800; color: var(--primary);">Configure Kanban Columns</h3>
        <form action="{{ route('statuses.store', $project) }}" method="POST">
            @csrf
            <div style="margin-bottom: 1rem;">
                <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Column Status Name</label>
                <input type="text" name="name" required placeholder="e.g. Security Audit" style="width:100%; padding:0.6rem; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main);">
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Column Color</label>
                <input type="color" name="color" value="#8b5cf6" style="width:100%; height:40px; border-radius:6px; border:1px solid var(--border-color); cursor:pointer;">
            </div>

            <div style="display:flex; justify-content:flex-end; gap:0.75rem;">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('manageStatusesModal').style.display='none'">Cancel</button>
                <button type="submit" class="btn btn-primary">Add Column</button>
            </div>
        </form>
    </div>
</div>

<script>
    const currentUserId = {{ auth()->id() }};
    const isSuperAdmin = {{ auth()->user()->isSuperAdmin() ? 'true' : 'false' }};

    function switchTab(tab) {
        window.location.href = '?tab=' + tab;
    }

    function openEditTaskModal(task) {
        document.getElementById('editTaskForm').action = '/tasks/' + task.id;
        document.getElementById('edit_task_title').value = task.title || '';
        document.getElementById('edit_task_type').value = task.type || 'feature';
        document.getElementById('edit_task_priority').value = task.priority || 'medium';
        document.getElementById('edit_task_status').value = task.status || 'New';
        document.getElementById('edit_task_start_date').value = task.start_date ? task.start_date.substring(0, 10) : '';
        document.getElementById('edit_task_due_date').value = task.due_date ? task.due_date.substring(0, 10) : '';
        document.getElementById('edit_task_description').value = task.description || '';
        
        if (task.assignees && $.fn.select2) {
            const assigneeIds = task.assignees.map(a => a.id);
            $('#edit_task_assignees').val(assigneeIds).trigger('change');
        }

        document.getElementById('editTaskModal').style.display = 'flex';
    }

    function openShowTaskModal(task) {
        document.getElementById('show_task_title').innerText = '[' + task.code + '] ' + task.title;
        document.getElementById('show_task_status').innerText = task.status;
        document.getElementById('show_task_priority').innerText = task.priority;
        document.getElementById('show_task_start_date').innerText = task.start_date ? task.start_date.substring(0, 10) : 'TBD';
        document.getElementById('show_task_due_date').innerText = task.due_date ? task.due_date.substring(0, 10) : 'TBD';
        document.getElementById('show_task_description').innerText = task.description || 'No description provided.';
        
        document.getElementById('taskCommentForm').action = '/comments/task/' + task.id;
        let commentsHtml = '';
        if (task.comments && task.comments.length > 0) {
            task.comments.forEach(c => {
                const date = new Date(c.created_at).toLocaleString();
                const userName = c.user ? c.user.name : 'Unknown';
                
                let actionsHtml = '';
                if (c.user_id === currentUserId || isSuperAdmin) {
                    actionsHtml = `
                        <div style="display:flex; gap:0.5rem; align-items:center;">
                            <button type="button" onclick="editComment(${c.id})" style="background:none; border:none; cursor:pointer; color:var(--text-muted);" title="Edit Comment">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                            </button>
                            <button type="button" onclick="deleteComment(${c.id})" style="background:none; border:none; cursor:pointer; color:var(--accent-rose);" title="Delete Comment">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                            </button>
                        </div>
                    `;
                }

                commentsHtml += `<div id="comment-wrapper-${c.id}" style="background: var(--bg-surface-elevated); padding: 0.75rem; border-radius: 8px; border: 1px solid var(--border-color); font-size: 0.85rem;">
                    <div style="display:flex; justify-content:space-between; margin-bottom: 0.4rem;">
                        <strong style="color: var(--primary);">${userName}</strong>
                        <div style="display:flex; gap:0.75rem; align-items:center;">
                            <span style="color: var(--text-muted); font-size: 0.75rem;">${date}</span>
                            ${actionsHtml}
                        </div>
                    </div>
                    <div id="comment-content-${c.id}" style="color: var(--text-main); white-space: pre-wrap;">${c.content}</div>
                </div>`;
            });
        } else {
            commentsHtml = '<div style="color: var(--text-muted); font-size: 0.85rem;">No comments yet.</div>';
        }
        document.getElementById('taskCommentsList').innerHTML = commentsHtml;

        document.getElementById('showTaskModal').style.display = 'flex';
    }

    function allowDrop(ev) {
        ev.preventDefault();
    }

    function dragTask(ev, taskId) {
        ev.dataTransfer.setData("text/plain", taskId);
    }

    function dropTask(ev, targetStatus) {
        ev.preventDefault();
        const taskId = ev.dataTransfer.getData("text/plain");
        if (!taskId) return;

        fetch(`/tasks/${taskId}/status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ status: targetStatus })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            }
        });
    }

    $(document).ready(function() {
        if ($.fn.select2) {
            $('.select2-assignees').select2({
                placeholder: "Search and select assignees...",
                allowClear: true,
                width: '100%',
                dropdownParent: $('#createTaskModal')
            });

            $('.select2-edit-assignees').select2({
                placeholder: "Search and select assignees...",
                allowClear: true,
                width: '100%',
                dropdownParent: $('#editTaskModal')
            });
        }
    });

    document.getElementById('taskCommentForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        const content = form.content.value;
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerText = 'Posting...';

        fetch(form.action, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ content: content })
        })
        .then(res => res.json())
        .then(data => {
            submitBtn.disabled = false;
            submitBtn.innerText = 'Post Comment';
            
            if (data.success) {
                form.content.value = '';
                const date = new Date(data.comment.created_at).toLocaleString();
                const userName = data.comment.user.name;
                let actionsHtml = `
                    <div style="display:flex; gap:0.5rem; align-items:center;">
                        <button type="button" onclick="editComment(${data.comment.id})" style="background:none; border:none; cursor:pointer; color:var(--text-muted);" title="Edit Comment">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                        </button>
                        <button type="button" onclick="deleteComment(${data.comment.id})" style="background:none; border:none; cursor:pointer; color:var(--accent-rose);" title="Delete Comment">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                        </button>
                    </div>
                `;
                const commentHtml = `<div id="comment-wrapper-${data.comment.id}" style="background: var(--bg-surface-elevated); padding: 0.75rem; border-radius: 8px; border: 1px solid var(--border-color); font-size: 0.85rem;">
                    <div style="display:flex; justify-content:space-between; margin-bottom: 0.4rem;">
                        <strong style="color: var(--primary);">${userName}</strong>
                        <div style="display:flex; gap:0.75rem; align-items:center;">
                            <span style="color: var(--text-muted); font-size: 0.75rem;">${date}</span>
                            ${actionsHtml}
                        </div>
                    </div>
                    <div id="comment-content-${data.comment.id}" style="color: var(--text-main); white-space: pre-wrap;">${data.comment.content}</div>
                </div>`;
                
                const list = document.getElementById('taskCommentsList');
                if (list.innerHTML.includes('No comments yet')) {
                    list.innerHTML = '';
                }
                list.insertAdjacentHTML('beforeend', commentHtml);
                list.scrollTop = list.scrollHeight;
            } else {
                alert('Error saving comment.');
            }
        })
        .catch(() => {
            submitBtn.disabled = false;
            submitBtn.innerText = 'Post Comment';
            alert('Network error.');
        });
    });

    window.editComment = function(id) {
        const contentDiv = document.getElementById('comment-content-' + id);
        const currentText = contentDiv.innerText;
        contentDiv.innerHTML = `
            <textarea id="edit-comment-input-${id}" rows="2" style="width:100%; padding:0.4rem; border-radius:6px; border:1px solid var(--primary); background:var(--bg-surface); color:var(--text-main); margin-bottom:0.5rem;">${currentText}</textarea>
            <div style="display:flex; justify-content:flex-end; gap:0.5rem;">
                <button type="button" class="btn btn-secondary" style="font-size:0.75rem; padding:0.2rem 0.5rem;" onclick="cancelEditComment(${id}, \`${currentText.replace(/`/g, '\\`')}\`)">Cancel</button>
                <button type="button" class="btn btn-primary" style="font-size:0.75rem; padding:0.2rem 0.5rem;" onclick="saveEditComment(${id})">Save</button>
            </div>
        `;
    };

    window.cancelEditComment = function(id, originalText) {
        document.getElementById('comment-content-' + id).innerText = originalText;
    };

    window.saveEditComment = function(id) {
        const newText = document.getElementById('edit-comment-input-' + id).value;
        fetch('/comments/' + id, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ content: newText })
        }).then(res => res.json()).then(data => {
            if(data.success) {
                document.getElementById('comment-content-' + id).innerText = newText;
            }
        });
    };

    window.deleteComment = function(id) {
        if(confirm('Delete this comment?')) {
            fetch('/comments/' + id, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            }).then(res => res.json()).then(data => {
                if(data.success) {
                    const wrapper = document.getElementById('comment-wrapper-' + id);
                    if(wrapper) wrapper.remove();
                }
            });
        }
    };
</script>
@endsection
