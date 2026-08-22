@extends('layouts.app')

@section('title', $book->title . ' - Wiki Book')

@section('content')
<div style="margin-bottom: 2rem;">
    <a href="{{ route('wikis.index') }}" style="color: var(--primary); text-decoration: none; font-weight: 600; font-size: 0.88rem;">&larr; Back to Wiki Hub</a>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 0.5rem; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h1 style="font-size: 1.85rem; font-weight: 800;">{{ $book->title }}</h1>
            <p style="color: var(--text-muted); font-size: 0.95rem; margin-top: 0.25rem;">
                Author: <strong>{{ $book->author->name }}</strong> |
                @if($book->owner)
                    Owner: <strong>{{ class_basename($book->owner_type) }}: {{ $book->owner->name }}</strong>
                @else
                    Owner: <strong>Unassigned / Private Wiki</strong>
                @endif
            </p>
        </div>

        <div style="display: flex; gap: 0.75rem; align-items: center;">
            @if($book->is_private || !$book->owner_type)
                <button class="btn btn-secondary" onclick="document.getElementById('shareBookModal').style.display='flex'">
                    Share Book
                </button>
            @endif

            @can('update', $book)
                <button class="btn btn-primary" onclick="document.getElementById('addChapterModal').style.display='flex'">
                    + Add Chapter
                </button>
            @endcan

            <div class="dropdown">
                <button class="btn btn-secondary" onclick="toggleDropdown('bookActionsMenu')">
                    <span>Actions</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="m6 9 6 6 6-6"/></svg>
                </button>
                <div class="dropdown-menu" id="bookActionsMenu">
                    @can('delete', $book)
                        <form action="{{ route('wikis.destroyBook', $book) }}" method="POST" onsubmit="return promptDelete('Wiki Book {{ addslashes($book->title) }}', this);" style="margin:0;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="dropdown-item" style="color: var(--accent-rose);">Soft Delete Book</button>
                        </form>
                    @endcan
                </div>
            </div>
        </div>
    </div>
    <p style="color: var(--text-muted); font-size: 0.95rem; margin-top: 0.5rem;">{{ $book->description }}</p>
</div>

<!-- Book Hierarchy Tree: Chapters -> Pages -->
<div style="display: flex; flex-direction: column; gap: 1.5rem;">
    @forelse($book->chapters as $index => $chap)
        <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 12px; padding: 1.5rem; box-shadow: var(--card-shadow);">
            
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.75rem;">
                <div>
                    <h3 style="font-size: 1.2rem; font-weight: 800; color: var(--primary);">
                        Chapter {{ $index + 1 }}: {{ $chap->title }}
                    </h3>
                    @if($chap->description)
                        <p style="font-size: 0.85rem; color: var(--text-muted); margin-top: 0.2rem;">{{ $chap->description }}</p>
                    @endif
                </div>

                <div style="display: flex; gap: 0.5rem; align-items: center;">
                    @can('update', $book)
                        <a href="{{ route('wikis.pages.create', $chap) }}" class="btn btn-primary" style="font-size: 0.78rem; padding: 0.35rem 0.75rem;">
                            + Add Page
                        </a>
                    @endcan

                    @can('delete', $book)
                        <form action="{{ route('wikis.destroyChapter', $chap) }}" method="POST" onsubmit="return promptDelete('Chapter {{ addslashes($chap->title) }}', this);" style="margin:0;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="font-size: 0.75rem; padding: 0.35rem 0.65rem;">Delete Chapter</button>
                        </form>
                    @endcan
                </div>
            </div>

            <!-- Chapter Pages Tabular View Table -->
            <div class="data-table-container" style="padding: 0; border: 1px solid var(--border-color); border-radius: 8px; overflow: hidden; box-shadow: none; margin-top: 1rem;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width: 45%;">Page Title</th>
                            <th style="width: 20%;">Author</th>
                            <th style="width: 20%;">Last Updated</th>
                            <th style="width: 15%; text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($chap->pages as $p)
                            <tr>
                                <td>
                                    <strong style="font-size: 0.92rem;">
                                        <a href="{{ route('wikis.showPage', $p) }}" style="color: var(--text-main); text-decoration: none;">
                                            {{ $p->title }}
                                        </a>
                                    </strong>
                                </td>
                                <td>
                                    <span style="font-size: 0.84rem; color: var(--text-muted);">
                                        {{ $p->author->name }}
                                    </span>
                                </td>
                                <td>
                                    <span style="font-size: 0.82rem; color: var(--text-muted);">
                                        {{ $p->updated_at->diffForHumans() }}
                                    </span>
                                </td>
                                <td style="text-align: right;">
                                    <div style="display: inline-flex; gap: 0.4rem; justify-content: flex-end; align-items: center;">
                                        <a href="{{ route('wikis.showPage', $p) }}" class="btn btn-primary" style="font-size: 0.76rem; padding: 0.25rem 0.6rem;">
                                            View &rarr;
                                        </a>
                                        @can('update', $book)
                                            <a href="{{ route('wikis.pages.edit', $p) }}" class="btn btn-secondary" style="font-size: 0.76rem; padding: 0.25rem 0.55rem;">
                                                Edit
                                            </a>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align: center; padding: 2rem; color: var(--text-muted);">
                                    No pages created in this chapter yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    @empty
        <div style="background: var(--bg-surface); border: 1px dashed var(--border-color); padding: 3rem; text-align: center; border-radius: 12px;">
            <p style="color: var(--text-muted);">This Wiki Book does not have any chapters yet.</p>
            @can('update', $book)
                <button class="btn btn-primary" style="margin-top: 1rem;" onclick="document.getElementById('addChapterModal').style.display='flex'">
                    + Add First Chapter
                </button>
            @endcan
        </div>
    @endforelse
</div>

<!-- Add Chapter Modal -->
@can('update', $book)
<div id="addChapterModal" style="display: none; position: fixed; top:0; left:0; right:0; bottom:0; background: rgba(0,0,0,0.6); align-items:center; justify-content:center; z-index:900;">
    <div style="background: var(--bg-surface); padding: 2rem; border-radius: 14px; width: 90%; max-width: 480px; border: 1px solid var(--border-color);">
        <h3 style="margin-bottom: 1rem; font-weight: 800; color: var(--primary);">Add Chapter to Book</h3>
        <form action="{{ route('wikis.storeChapter', $book) }}" method="POST">
            @csrf
            <div style="margin-bottom: 1rem;">
                <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Chapter Title</label>
                <input type="text" name="title" placeholder="e.g. Getting Started & Setup" required style="width:100%; padding:0.6rem; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main);">
            </div>
            <div style="margin-bottom: 1.5rem;">
                <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Description</label>
                <textarea name="description" rows="3" style="width:100%; padding:0.6rem; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main);"></textarea>
            </div>
            <div style="display:flex; justify-content:flex-end; gap:0.75rem;">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('addChapterModal').style.display='none'">Cancel</button>
                <button type="submit" class="btn btn-primary">Create Chapter</button>
            </div>
        </form>
    </div>
</div>
@endcan

<!-- Share Book Modal -->
<div id="shareBookModal" style="display: none; position: fixed; top:0; left:0; right:0; bottom:0; background: rgba(0,0,0,0.6); align-items:center; justify-content:center; z-index:900;">
    <div style="background: var(--bg-surface); padding: 2rem; border-radius: 14px; width: 90%; max-width: 480px; border: 1px solid var(--border-color);">
        <h3 style="margin-bottom: 1rem; font-weight: 800; color: var(--primary);">Share Private Wiki Book</h3>
        
        <form action="{{ route('wikis.shareBook', $book) }}" method="POST" style="margin-bottom: 1.5rem;">
            @csrf
            <div style="margin-bottom: 1rem;">
                <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Grant Access to User</label>
                <select name="user_id" required style="width:100%; padding:0.6rem; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main);">
                    @foreach($allUsers as $u)
                        <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center;">Grant Share Access</button>
        </form>

        <h4 style="font-size: 0.88rem; font-weight: 700; margin-bottom: 0.5rem;">Currently Shared With:</h4>
        <div style="display: flex; flex-direction: column; gap: 0.4rem; max-height: 150px; overflow-y: auto;">
            @forelse($book->sharedUsers as $su)
                <div style="font-size: 0.85rem; background: var(--bg-surface-elevated); padding: 0.4rem 0.6rem; border-radius: 6px;">
                    User: {{ $su->name }} ({{ $su->email }})
                </div>
            @empty
                <p style="font-size: 0.82rem; color: var(--text-muted);">Not shared with any secondary users yet.</p>
            @endforelse
        </div>

        <div style="display:flex; justify-content:flex-end; margin-top: 1rem;">
            <button type="button" class="btn btn-secondary" onclick="document.getElementById('shareBookModal').style.display='none'">Close</button>
        </div>
    </div>
</div>
@endsection
