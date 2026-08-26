@extends('layouts.app')

@section('title', 'Organizations - PragmaTick Command Center')

@section('content')
<!-- Standardized Universal Page Header Bar -->
<div class="page-header-bar">
    <div>
        <h1 class="page-header-title">Organizations Directory</h1>
        <p class="page-header-subtext">
            Enterprise organizational units, role scopes, and associated projects
        </p>
    </div>

    <div class="page-header-actions">
        <input type="text" id="orgSearchInput" onkeyup="filterOrgTable()" placeholder="Filter organizations..." style="font-size: 0.82rem; padding: 0.45rem 0.8rem; border-radius: 8px; border: 1px solid var(--border-color); background: var(--bg-surface-elevated); color: var(--text-main); width: 220px; outline: none;">

        @can('create', \App\Models\Organization::class)
            <button class="btn btn-primary" onclick="document.getElementById('createOrgModal').style.display='flex'">
                + Create Organization
            </button>
        @endcan
    </div>
</div>

<!-- Tabular Directory View Container -->
<div class="data-table-container">
    <table class="data-table" id="orgDirectoryTable">
        <thead>
            <tr>
                <th style="width: 32%;">Organization & Description</th>
                <th style="width: 15%;">Role Scope</th>
                <th style="width: 15%;">Active Projects</th>
                <th style="width: 15%;">Assigned Users</th>
                <th style="width: 23%; text-align: right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($organizations as $org)
                <tr class="org-table-row" data-search="{{ strtolower($org->name . ' ' . $org->description . ' ' . ($user->isSuperAdmin() || $user->isOrgAdmin($org->id) ? 'org admin' : 'member')) }}">
                    <td>
                        <div style="display: flex; align-items: flex-start; gap: 0.75rem;">
                            <div style="width: 12px; height: 12px; border-radius: 3px; background: {{ $org->color_code }}; flex-shrink: 0; margin-top: 0.3rem;"></div>
                            <div>
                                <strong style="font-size: 0.95rem;">
                                    <a href="{{ route('organizations.show', $org) }}" style="color: var(--text-main); text-decoration: none;">
                                        {{ $org->name }}
                                    </a>
                                </strong>
                                <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.15rem;">
                                    {{ Str::limit($org->description, 95, '...') }}
                                </p>
                            </div>
                        </div>
                    </td>
                    <td>
                        @if($user->isSuperAdmin() || $user->isOrgAdmin($org->id))
                            <strong style="font-weight: 700; color: var(--primary); font-size: 0.85rem;">Org Admin</strong>
                        @else
                            <strong style="font-weight: 700; color: var(--text-muted); font-size: 0.85rem;">Member</strong>
                        @endif
                    </td>
                    <td>
                        <strong style="font-weight: 700; color: var(--text-main); font-size: 0.85rem;">{{ $org->projects_count }} Projects</strong>
                    </td>
                    <td>
                        <span style="font-size: 0.84rem; font-weight: 600; color: var(--text-main);">
                            {{ $org->users_count }} Members
                        </span>
                    </td>
                    <td style="text-align: right;">
                        <div style="display: inline-flex; gap: 0.5rem; justify-content: flex-end; align-items: center;">
                            <a href="{{ route('organizations.show', $org) }}" class="btn btn-secondary" style="font-size: 0.78rem; padding: 0.3rem 0.65rem;">
                                View Details →
                            </a>

                            @can('delete', $org)
                                <form action="{{ route('organizations.destroy', $org) }}" method="POST" onsubmit="return promptDelete('{{ addslashes($org->name) }} Organization', this);" style="margin:0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" style="font-size: 0.75rem; padding: 0.3rem 0.65rem;">
                                        Delete
                                    </button>
                                </form>
                            @endcan
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 2.5rem; color: var(--text-muted);">
                        No organizations found within your access scope.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Create Organization Modal -->
@can('create', \App\Models\Organization::class)
<div id="createOrgModal" class="modal-backdrop">
    <div class="modal-card" style="max-width: 480px;">
        <div class="modal-header">
            <h3 style="font-weight: 800; color: var(--primary);">Create New Organization</h3>
            <button type="button" onclick="document.getElementById('createOrgModal').style.display='none'" style="background:none; border:none; color: var(--text-muted); cursor:pointer;">✕</button>
        </div>
        <form action="{{ route('organizations.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div style="margin-bottom: 1rem;">
                    <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Organization Name</label>
                    <input type="text" name="name" placeholder="e.g. Apex Global Infrastructure" required style="width:100%;">
                </div>
                <div style="margin-bottom: 1rem;">
                    <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Primary Color</label>
                    <input type="color" name="color_code" value="#008b8b" style="width:100%; height:40px; border-radius:6px; border:1px solid var(--border-color); cursor:pointer;">
                </div>
                <div style="margin-bottom: 1rem;">
                    <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Description</label>
                    <textarea name="description" rows="3" style="width:100%;"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('createOrgModal').style.display='none'">Cancel</button>
                <button type="submit" class="btn btn-primary">Create Organization</button>
            </div>
        </form>
    </div>
</div>
@endcan

<script>
    function filterOrgTable() {
        const input = document.getElementById('orgSearchInput');
        const query = input ? input.value.toLowerCase() : '';
        const rows = document.querySelectorAll('.org-table-row');
        
        rows.forEach(row => {
            const searchStr = row.getAttribute('data-search') || '';
            if (searchStr.includes(query)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
</script>
@endsection
