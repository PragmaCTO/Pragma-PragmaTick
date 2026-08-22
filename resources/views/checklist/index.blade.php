@extends('layouts.app')

@section('title', 'Personal Checklist - PragmaTick Command Center')

@section('content')
<style>
    .kanban-grid-4 {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.25rem;
        min-height: 500px;
    }

    @media (max-width: 1024px) {
        .kanban-grid-4 {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 640px) {
        .kanban-grid-4 {
            grid-template-columns: 1fr;
        }
    }

    .kanban-col {
        background: var(--bg-surface);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 1.25rem;
        display: flex;
        flex-direction: column;
        gap: 1rem;
        box-shadow: var(--card-shadow);
    }

    .kanban-card {
        background: var(--bg-surface-elevated);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        padding: 1rem;
        cursor: grab;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }

    .kanban-card:active {
        cursor: grabbing;
        transform: scale(0.98);
    }
</style>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem; background: var(--bg-surface); padding: 1.25rem 1.5rem; border-radius: 12px; border: 1px solid var(--border-color); box-shadow: var(--shadow-sm);">
    <div>
        <h1 style="font-size: 1.65rem; font-weight: 800;">Personal Private Checklist</h1>
        <p style="color: var(--text-muted); font-size: 0.88rem; margin-top: 0.2rem;">
            Unlinked private task management for <strong>{{ $user->name }}</strong>
        </p>
    </div>

    <div style="display: flex; gap: 0.75rem; align-items: center;">
        <div style="background: var(--bg-surface-elevated); padding: 0.2rem; border-radius: 8px; border: 1px solid var(--border-color); display: flex; gap: 0.2rem;">
            <a href="{{ route('checklist.index', ['view' => 'kanban']) }}" class="btn {{ $viewMode === 'kanban' ? 'btn-primary' : 'btn-secondary' }}" style="font-size: 0.8rem; padding: 0.35rem 0.75rem;">
                <span>Kanban Board</span>
            </a>
            <a href="{{ route('checklist.index', ['view' => 'table']) }}" class="btn {{ $viewMode === 'table' ? 'btn-primary' : 'btn-secondary' }}" style="font-size: 0.8rem; padding: 0.35rem 0.75rem;">
                <span>Tabular View</span>
            </a>
        </div>

        <button class="btn btn-primary" onclick="document.getElementById('createChecklistModal').style.display='flex'">
            + New Checklist Item
        </button>
    </div>
</div>

@if($viewMode === 'kanban')
    <!-- 4-Column Kanban View: To-Do, In-Progress, Completed, Delayed -->
    <div class="kanban-grid-4">
        @foreach($statuses as $st)
            @php
                $colItems = $itemsByStatus[$st] ?? collect();
                $tagClass = match($st) {
                    'To-Do' => 'tag-cyan',
                    'In-Progress' => 'tag-amber',
                    'Completed' => 'tag-green',
                    'Delayed' => 'tag-rose',
                    default => 'tag-cyan'
                };
            @endphp
            
            <div class="kanban-col" data-status="{{ $st }}" ondragover="allowDrop(event)" ondrop="dropChecklist(event, '{{ $st }}')">
                <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid var(--border-color); padding-bottom: 0.6rem;">
                    <strong style="font-size: 0.95rem;">{{ $st }}</strong>
                    <span class="tag {{ $tagClass }}">{{ $colItems->count() }}</span>
                </div>

                @forelse($colItems as $item)
                    <div class="kanban-card" id="checklist-card-{{ $item->id }}" draggable="true" ondragstart="dragChecklist(event, {{ $item->id }})">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.4rem;">
                            <strong style="font-size: 0.92rem; color: var(--text-main);">{{ $item->title }}</strong>
                            <span class="tag tag-cyan" style="font-size: 0.65rem; text-transform: uppercase;">{{ $item->priority }}</span>
                        </div>

                        @if($item->description)
                            <p style="font-size: 0.82rem; color: var(--text-muted); margin-bottom: 0.6rem;">{{ Str::limit($item->description, 80) }}</p>
                        @endif

                        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.75rem; color: var(--text-muted); border-top: 1px solid var(--border-color); padding-top: 0.5rem; margin-top: 0.5rem;">
                            <span>Start: {{ $item->start_date ? $item->start_date->format('M d') : 'TBD' }} | Due: {{ $item->due_date ? $item->due_date->format('M d') : 'TBD' }}</span>
                            
                            <div style="display: flex; gap: 0.4rem;">
                                <button class="btn btn-secondary" style="font-size: 0.7rem; padding: 0.15rem 0.4rem;" onclick="openEditChecklistModal({{ json_encode($item) }})">Edit</button>
                                <form action="{{ route('checklist.destroy', $item) }}" method="POST" onsubmit="return promptDelete('Checklist item {{ addslashes($item->title) }}', this);" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" style="font-size: 0.7rem; padding: 0.15rem 0.4rem;">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div style="text-align: center; color: var(--text-muted); font-size: 0.82rem; padding: 2rem 0; border: 1px dashed var(--border-color); border-radius: 8px;">
                        Drop items here
                    </div>
                @endforelse
            </div>
        @endforeach
    </div>
@else
    <!-- Tabular List View -->
    <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 12px; padding: 1.5rem; box-shadow: var(--card-shadow);">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 0.88rem;">
                <thead>
                    <tr style="border-bottom: 1px solid var(--border-color); background: var(--bg-surface-elevated);">
                        <th style="padding: 0.75rem; text-align: left;">Checklist Item Title</th>
                        <th style="padding: 0.75rem; text-align: left;">Priority</th>
                        <th style="padding: 0.75rem; text-align: left;">Status</th>
                        <th style="padding: 0.75rem; text-align: left;">Start Date</th>
                        <th style="padding: 0.75rem; text-align: left;">Due Date</th>
                        <th style="padding: 0.75rem; text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        <tr style="border-bottom: 1px solid var(--border-color);">
                            <td style="padding: 0.75rem;">
                                <strong>{{ $item->title }}</strong>
                                @if($item->description)
                                    <br><span style="font-size: 0.8rem; color: var(--text-muted);">{{ $item->description }}</span>
                                @endif
                            </td>
                            <td style="padding: 0.75rem;">
                                <strong style="font-weight: 700; color: var(--text-main); font-size: 0.82rem; text-transform: capitalize;">{{ $item->priority }}</strong>
                            </td>
                            <td style="padding: 0.75rem;">
                                <strong style="font-weight: 700; color: var(--primary); font-size: 0.82rem;">{{ $item->status }}</strong>
                            </td>
                            <td style="padding: 0.75rem;">{{ $item->start_date ? $item->start_date->format('Y-m-d') : 'None' }}</td>
                            <td style="padding: 0.75rem;">{{ $item->due_date ? $item->due_date->format('Y-m-d') : 'None' }}</td>
                            <td style="padding: 0.75rem; text-align: right;">
                                <button class="btn btn-secondary" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;" onclick="openEditChecklistModal({{ json_encode($item) }})">Edit</button>
                                <form action="{{ route('checklist.destroy', $item) }}" method="POST" onsubmit="return promptDelete('Checklist item {{ addslashes($item->title) }}', this);" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="padding: 2rem; text-align: center; color: var(--text-muted);">No private checklist items added yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endif

<!-- Create Checklist Item Modal -->
<div id="createChecklistModal" style="display: none; position: fixed; top:0; left:0; right:0; bottom:0; background: rgba(0,0,0,0.6); align-items:center; justify-content:center; z-index:900; backdrop-filter: blur(4px);">
    <div style="background: var(--bg-surface); padding: 2rem; border-radius: 14px; width: 90%; max-width: 500px; border: 1px solid var(--border-color);">
        <h3 style="margin-bottom: 1rem; font-weight: 800; color: var(--primary);">+ New Private Checklist Item</h3>
        
        <form action="{{ route('checklist.store') }}" method="POST">
            @csrf
            <div style="margin-bottom: 1rem;">
                <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Title</label>
                <input type="text" name="title" required placeholder="e.g. Review Q3 Security Audit" style="width:100%; padding:0.6rem; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main);">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Priority</label>
                    <select name="priority" required style="width:100%; padding:0.6rem; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main);">
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                        <option value="urgent">Urgent</option>
                    </select>
                </div>
                <div>
                    <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Kanban Status</label>
                    <select name="status" required style="width:100%; padding:0.6rem; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main);">
                        <option value="To-Do">To-Do</option>
                        <option value="In-Progress">In-Progress</option>
                        <option value="Completed">Completed</option>
                        <option value="Delayed">Delayed</option>
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

            <div style="margin-bottom: 1.5rem;">
                <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Description</label>
                <textarea name="description" rows="3" style="width:100%; padding:0.6rem; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main);"></textarea>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:0.75rem;">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('createChecklistModal').style.display='none'">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Item</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Checklist Item Modal -->
<div id="editChecklistModal" style="display: none; position: fixed; top:0; left:0; right:0; bottom:0; background: rgba(0,0,0,0.6); align-items:center; justify-content:center; z-index:900; backdrop-filter: blur(4px);">
    <div style="background: var(--bg-surface); padding: 2rem; border-radius: 14px; width: 90%; max-width: 500px; border: 1px solid var(--border-color);">
        <h3 style="margin-bottom: 1rem; font-weight: 800; color: var(--primary);">Edit Checklist Item</h3>
        
        <form action="" method="POST" id="editChecklistForm">
            @csrf
            @method('PUT')
            
            <div style="margin-bottom: 1rem;">
                <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Title</label>
                <input type="text" name="title" id="chk_title" required style="width:100%; padding:0.6rem; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main);">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Priority</label>
                    <select name="priority" id="chk_priority" required style="width:100%; padding:0.6rem; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main);">
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                        <option value="urgent">Urgent</option>
                    </select>
                </div>
                <div>
                    <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Status</label>
                    <select name="status" id="chk_status" required style="width:100%; padding:0.6rem; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main);">
                        <option value="To-Do">To-Do</option>
                        <option value="In-Progress">In-Progress</option>
                        <option value="Completed">Completed</option>
                        <option value="Delayed">Delayed</option>
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Start Date</label>
                    <input type="date" name="start_date" id="chk_start_date" style="width:100%; padding:0.6rem; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main);">
                </div>
                <div>
                    <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Due Date</label>
                    <input type="date" name="due_date" id="chk_due_date" style="width:100%; padding:0.6rem; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main);">
                </div>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Description</label>
                <textarea name="description" id="chk_description" rows="3" style="width:100%; padding:0.6rem; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main);"></textarea>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:0.75rem;">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('editChecklistModal').style.display='none'">Cancel</button>
                <button type="submit" class="btn btn-primary">Update Item</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditChecklistModal(item) {
        document.getElementById('editChecklistForm').action = '/checklist/' + item.id;
        document.getElementById('chk_title').value = item.title || '';
        document.getElementById('chk_priority').value = item.priority || 'medium';
        document.getElementById('chk_status').value = item.status || 'To-Do';
        document.getElementById('chk_start_date').value = item.start_date ? item.start_date.substring(0, 10) : '';
        document.getElementById('chk_due_date').value = item.due_date ? item.due_date.substring(0, 10) : '';
        document.getElementById('chk_description').value = item.description || '';
        document.getElementById('editChecklistModal').style.display = 'flex';
    }

    function allowDrop(ev) {
        ev.preventDefault();
    }

    function dragChecklist(ev, itemId) {
        ev.dataTransfer.setData("text/plain", itemId);
    }

    function dropChecklist(ev, targetStatus) {
        ev.preventDefault();
        const itemId = ev.dataTransfer.getData("text/plain");
        if (!itemId) return;

        fetch(`/checklist/${itemId}/status`, {
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
</script>
@endsection
