@extends('layouts.app')

@section('title', 'Wiki Documentation - PragmaTick Command Center')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem; background: var(--bg-surface); padding: 1.25rem 1.5rem; border-radius: 12px; border: 1px solid var(--border-color); box-shadow: var(--shadow-sm);">
    <div>
        <h1 style="font-size: 1.65rem; font-weight: 800;">Wiki Documentation Hub</h1>
        <p style="color: var(--text-muted); font-size: 0.88rem; margin-top: 0.2rem;">
            Multi-tiered Book &rarr; Chapter &rarr; Page documentation repository
        </p>
    </div>

    <button class="btn btn-primary" onclick="document.getElementById('createBookModal').style.display='flex'">
        + New Wiki Book
    </button>
</div>

<!-- Books Grid -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1.5rem;">
    @forelse($books as $book)
        <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 12px; padding: 1.5rem; box-shadow: var(--card-shadow);">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.75rem;">
                <h3 style="font-size: 1.15rem; font-weight: 700;">
                    <a href="{{ route('wikis.showBook', $book) }}" style="color: var(--text-main); text-decoration: none;">
                        {{ $book->title }}
                    </a>
                </h3>

                @if($book->is_private)
                    <span class="tag tag-rose">PRIVATE</span>
                @elseif($book->owner_type === \App\Models\Organization::class)
                    <span class="tag tag-cyan">ORG WIKI</span>
                @elseif($book->owner_type === \App\Models\Project::class)
                    <span class="tag tag-amber">PROJECT WIKI</span>
                @else
                    <span class="tag tag-green">PUBLIC</span>
                @endif
            </div>

            <p style="font-size: 0.88rem; color: var(--text-muted); margin-bottom: 1.25rem; min-height: 2.5rem;">
                {{ Str::limit($book->description, 100) }}
            </p>

            <div style="font-size: 0.82rem; color: var(--text-muted); margin-bottom: 1rem; background: var(--bg-surface-elevated); padding: 0.6rem 0.8rem; border-radius: 6px; border: 1px solid var(--border-color);">
                @if($book->owner)
                    Owner: <strong>{{ class_basename($book->owner_type) }}: {{ $book->owner->name }}</strong>
                @else
                    Owner: <strong>Unassigned / Author ({{ $book->author->name }})</strong>
                @endif
                <div style="margin-top: 0.2rem;">Chapters: <strong>{{ $book->chapters->count() }}</strong></div>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--border-color); padding-top: 0.75rem;">
                <a href="{{ route('wikis.showBook', $book) }}" class="btn btn-primary" style="font-size: 0.8rem; padding: 0.35rem 0.75rem;">
                    Read Book &rarr;
                </a>

                @can('delete', $book)
                    <form action="{{ route('wikis.destroyBook', $book) }}" method="POST" onsubmit="return promptDelete('Wiki Book {{ addslashes($book->title) }}', this);">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" style="font-size: 0.75rem; padding: 0.35rem 0.65rem;">
                            Delete
                        </button>
                    </form>
                @endcan
            </div>
        </div>
    @empty
        <div style="grid-column: 1 / -1; background: var(--bg-surface); border: 1px dashed var(--border-color); padding: 3rem; text-align: center; border-radius: 12px;">
            <p style="color: var(--text-muted);">No Wiki Books found in your scope.</p>
        </div>
    @endforelse
</div>

<!-- Create Wiki Book Modal -->
<div id="createBookModal" style="display: none; position: fixed; top:0; left:0; right:0; bottom:0; background: rgba(0,0,0,0.6); align-items:center; justify-content:center; z-index:900; backdrop-filter: blur(4px);">
    <div style="background: var(--bg-surface); padding: 2rem; border-radius: 14px; width: 90%; max-width: 500px; border: 1px solid var(--border-color);">
        <h3 style="margin-bottom: 1rem; font-weight: 800; color: var(--primary);">+ Create New Wiki Book</h3>
        <form action="{{ route('wikis.storeBook') }}" method="POST">
            @csrf
            <div style="margin-bottom: 1rem;">
                <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Book Title</label>
                <input type="text" name="title" placeholder="e.g. System Architecture Manual" required style="width:100%; padding:0.6rem; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main);">
            </div>
            
            <div style="margin-bottom: 1rem;">
                <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Owner Assignment</label>
                <select name="owner_kind" id="ownerKindSelect" onchange="toggleOwnerIdSelect(this.value)" required style="width:100%; padding:0.6rem; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main);">
                    <option value="organization">Organization Level Wiki</option>
                    <option value="project">Project Level Wiki</option>
                    <option value="private">Unassigned / Private Wiki</option>
                </select>
            </div>

            <div id="orgIdContainer" style="margin-bottom: 1rem;">
                <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Select Organization</label>
                <select name="owner_id" id="orgOwnerSelect" style="width:100%; padding:0.6rem; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main);">
                    @foreach($organizations as $org)
                        <option value="{{ $org->id }}">{{ $org->name }}</option>
                    @endforeach
                </select>
            </div>

            <div id="projIdContainer" style="margin-bottom: 1rem; display: none;">
                <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Select Project</label>
                <select name="owner_id_proj" id="projOwnerSelect" style="width:100%; padding:0.6rem; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main);">
                    @foreach($projects as $proj)
                        <option value="{{ $proj->id }}">{{ $proj->name }} ({{ $proj->organization->name }})</option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Description</label>
                <textarea name="description" rows="3" style="width:100%; padding:0.6rem; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main);"></textarea>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:0.75rem;">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('createBookModal').style.display='none'">Cancel</button>
                <button type="submit" class="btn btn-primary">Create Book</button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleOwnerIdSelect(val) {
        const orgCont = document.getElementById('orgIdContainer');
        const projCont = document.getElementById('projIdContainer');
        const orgSel = document.getElementById('orgOwnerSelect');
        const projSel = document.getElementById('projOwnerSelect');

        if (val === 'organization') {
            orgCont.style.display = 'block';
            projCont.style.display = 'none';
            orgSel.name = 'owner_id';
            projSel.removeAttribute('name');
        } else if (val === 'project') {
            orgCont.style.display = 'none';
            projCont.style.display = 'block';
            projSel.name = 'owner_id';
            orgSel.removeAttribute('name');
        } else {
            orgCont.style.display = 'none';
            projCont.style.display = 'none';
            orgSel.removeAttribute('name');
            projSel.removeAttribute('name');
        }
    }
</script>
@endsection
