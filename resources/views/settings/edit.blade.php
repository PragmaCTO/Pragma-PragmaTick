@extends('layouts.app')

@section('title', 'Settings & User Console - PragmaTick')

@section('content')
<!-- Standardized Universal Page Header -->
<div class="page-header-bar">
    <div>
        <h1 class="page-header-title">Settings & User Console</h1>
        <p class="page-header-subtext">
            Account profile configuration, secondary emails, and Super Admin user administration.
        </p>
    </div>
</div>

<!-- Vertical Tabbed Layout Container -->
<div style="display: grid; grid-template-columns: 240px 1fr; gap: 1.75rem; align-items: start;">
    
    <!-- Left Vertical Tab Menu -->
    <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 12px; padding: 0.75rem; box-shadow: var(--shadow-sm); display: flex; flex-direction: column; gap: 0.25rem;">
        <button type="button" class="tab-vertical-btn active" id="tabBtnProfile" onclick="switchSettingsTab('profileTab', this)">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            <span>Personal Profile</span>
        </button>

        @if($user->isSuperAdmin() || $isOrgAdmin)
            <button type="button" class="tab-vertical-btn" id="tabBtnDirectory" onclick="switchSettingsTab('directoryTab', this)">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                <span>User Directory</span>
            </button>
        @endif

        @if($user->isSuperAdmin())
            <button type="button" class="tab-vertical-btn" id="tabBtnUsers" onclick="switchSettingsTab('usersTab', this)">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v8"/><path d="M8 12h8"/></svg>
                <span>User Provisioning</span>
            </button>

            <a href="{{ route('recovery.index') }}" class="tab-vertical-btn" style="text-decoration: none;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/><path d="m9 12 2 2 4-4"/></svg>
                <span>Trash Bin & Recovery</span>
            </a>
        @endif
    </div>

    <!-- Right Tab Content Panel -->
    <div>
        <!-- Tab 1: Personal Profile & Managed Emails -->
        <div id="profileTab" class="settings-tab-panel" style="display: block;">
            <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 12px; padding: 1.75rem; box-shadow: var(--shadow-sm);">
                <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--primary); margin-bottom: 1rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem;">
                    Personal Profile & Contact Information
                </h3>

                <form action="{{ route('settings.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div style="margin-bottom: 1.25rem;">
                        <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required style="width: 100%;">
                    </div>

                    <div style="margin-bottom: 1.5rem;">
                        <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Phone Number</label>
                        <input type="text" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" placeholder="+1-555-0100" style="width: 100%;">
                    </div>

                    <!-- JSON Emails Array Field Editor -->
                    <div style="margin-bottom: 1.75rem;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.4rem;">
                            <label style="font-size: 0.85rem; font-weight: 700;">Managed Email Addresses (JSON Array)</label>
                            <button type="button" class="btn btn-secondary" onclick="addEmailField()" style="font-size: 0.75rem; padding: 0.2rem 0.5rem;">+ Add Secondary Email</button>
                        </div>
                        <p style="font-size: 0.78rem; color: var(--text-muted); margin-bottom: 0.6rem;">Primary email address will be set to the first email in the array.</p>

                        <div id="emailsListContainer" style="display: flex; flex-direction: column; gap: 0.5rem;">
                            @php
                                $userEmails = is_array($user->emails) ? $user->emails : [$user->email];
                            @endphp
                            @foreach($userEmails as $idx => $emailVal)
                                <div class="email-input-row" style="display: flex; gap: 0.5rem;">
                                    <input type="email" name="emails[]" value="{{ $emailVal }}" required style="flex: 1;">
                                    @if($idx > 0)
                                        <button type="button" class="btn btn-danger" onclick="this.parentElement.remove()" style="padding: 0.6rem 0.8rem;">✕</button>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div style="display: flex; justify-content: flex-end; border-top: 1px solid var(--border-color); padding-top: 1rem;">
                        <button type="submit" class="btn btn-primary">Save Profile Settings</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tab 2: User Directory & Scoped Administration -->
        @if($user->isSuperAdmin() || $isOrgAdmin)
            <div id="directoryTab" class="settings-tab-panel" style="display: none;">
                <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 12px; padding: 1.75rem; box-shadow: var(--shadow-sm);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem;">
                        <div>
                            <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--primary);">User Directory & Administration</h3>
                            <p style="font-size: 0.82rem; color: var(--text-muted);">
                                @if($user->isSuperAdmin())
                                    Super Admin view displaying all users across all organizations and projects.
                                @else
                                    Organization Admin view displaying member users within your administered organization(s).
                                @endif
                            </p>
                        </div>
                        <strong style="font-size: 0.88rem; font-weight: 700; color: var(--primary);">{{ $managedUsers->count() }} Users</strong>
                    </div>

                    <div class="data-table-container" style="padding: 0; border: none; box-shadow: none; overflow-x: auto;">
                        <table class="data-table" style="width: 100%; border-collapse: collapse; font-size: 0.88rem;">
                            <thead>
                                <tr style="border-bottom: 1px solid var(--border-color); background: var(--bg-surface-elevated);">
                                    <th style="padding: 0.75rem; text-align: left;">User Name & Email</th>
                                    <th style="padding: 0.75rem; text-align: left;">Global Role</th>
                                    <th style="padding: 0.75rem; text-align: left;">Organizations</th>
                                    <th style="padding: 0.75rem; text-align: left;">Projects</th>
                                    <th style="padding: 0.75rem; text-align: right;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($managedUsers as $mu)
                                    <tr style="border-bottom: 1px solid var(--border-color);">
                                        <td style="padding: 0.75rem;">
                                            <strong style="color: var(--text-main);">{{ $mu->name }}</strong>
                                            <br><span style="font-size: 0.8rem; color: var(--text-muted);">{{ $mu->email }}</span>
                                        </td>
                                        <td style="padding: 0.75rem;">
                                            @if($mu->isSuperAdmin())
                                                <strong style="font-weight: 700; color: var(--accent-rose); font-size: 0.82rem;">Super Admin</strong>
                                            @else
                                                <strong style="font-weight: 700; color: var(--text-muted); font-size: 0.82rem;">Standard Member</strong>
                                            @endif
                                        </td>
                                        <td style="padding: 0.75rem; vertical-align: top;">
                                            @if($mu->organizations->isNotEmpty())
                                                <ul style="margin: 0; padding-left: 1.1rem; font-size: 0.82rem; color: var(--text-main);">
                                                    @foreach($mu->organizations as $org)
                                                        <li style="margin-bottom: 0.2rem;">
                                                            <strong style="color: var(--primary);">{{ $org->name }}</strong>
                                                            <span style="color: var(--text-muted); font-size: 0.78rem;">({{ $org->pivot->role }})</span>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <span style="font-size: 0.82rem; color: var(--text-muted);">Unassigned</span>
                                            @endif
                                        </td>
                                        <td style="padding: 0.75rem; vertical-align: top;">
                                            @if($mu->projects->isNotEmpty())
                                                <ul style="margin: 0; padding-left: 1.1rem; font-size: 0.82rem; color: var(--text-main);">
                                                    @foreach($mu->projects as $proj)
                                                        <li style="margin-bottom: 0.2rem;">{{ $proj->name }}</li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <span style="font-size: 0.82rem; color: var(--text-muted);">None</span>
                                            @endif
                                        </td>
                                        <td style="padding: 0.75rem; text-align: right; vertical-align: top;">
                                            <div style="display: flex; gap: 0.35rem; justify-content: flex-end;">
                                                @if($user->isSuperAdmin())
                                                    <button type="button" class="btn btn-secondary" style="font-size: 0.75rem; padding: 0.25rem 0.55rem;" onclick="openEditUserModal({{ json_encode($mu) }})">
                                                        Edit
                                                    </button>
                                                @endif
                                                @if($user->id !== $mu->id && ($user->isSuperAdmin() || !$mu->isSuperAdmin()))
                                                    <form action="{{ route('users.destroy', $mu) }}" method="POST" onsubmit="return promptDelete('User {{ addslashes($mu->name) }}', this);" style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger" style="font-size: 0.75rem; padding: 0.25rem 0.55rem;">
                                                            Soft Delete
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" style="padding: 2rem; text-align: center; color: var(--text-muted);">No users found in your scope.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        <!-- Tab 3: Super Admin User Creation Console (Restricted to Super Admins) -->
        @if($user->isSuperAdmin())
            <div id="usersTab" class="settings-tab-panel" style="display: none;">
                <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 12px; padding: 1.75rem; box-shadow: var(--shadow-sm);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem;">
                        <div>
                            <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--primary);">User Account Provisioning Console</h3>
                            <p style="font-size: 0.82rem; color: var(--text-muted);">Super Admins are the only role permitted to physically create new users.</p>
                        </div>
                        <span class="tag tag-rose">SUPER ADMIN GATE</span>
                    </div>

                    <form action="{{ route('users.store') }}" method="POST">
                        @csrf

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                            <div>
                                <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Full Name</label>
                                <input type="text" name="name" required placeholder="e.g. Marcus Brody" style="width: 100%;">
                            </div>
                            <div>
                                <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Primary Email Address</label>
                                <input type="email" name="email" required placeholder="marcus@company.com" style="width: 100%;">
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                            <div>
                                <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Phone Number</label>
                                <input type="text" name="phone_number" placeholder="+1-555-0188" style="width: 100%;">
                            </div>
                            <div>
                                <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Initial Password</label>
                                <input type="password" name="password" required minlength="8" placeholder="••••••••" style="width: 100%;">
                            </div>
                        </div>

                        <div style="margin-bottom: 1.25rem;">
                            <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.88rem; font-weight: 700; cursor: pointer;">
                                <input type="checkbox" name="is_super_admin" value="1">
                                <span>Grant Global Super Admin Privileges</span>
                            </label>
                        </div>

                        <div style="display: flex; justify-content: flex-end; border-top: 1px solid var(--border-color); padding-top: 1rem;">
                            <button type="submit" class="btn btn-primary">Create User Account</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Edit User Modal (Super Admin Only) -->
@if($user->isSuperAdmin())
<div id="editUserModal" style="display: none; position: fixed; top:0; left:0; right:0; bottom:0; background: rgba(0,0,0,0.6); align-items:center; justify-content:center; z-index:900; backdrop-filter: blur(4px);">
    <div style="background: var(--bg-surface); padding: 2rem; border-radius: 14px; width: 90%; max-width: 520px; border: 1px solid var(--border-color); max-height: 85vh; overflow-y: auto;">
        <h3 style="margin-bottom: 1rem; font-weight: 800; color: var(--primary);">Edit User Details & Security Credentials</h3>
        
        <form action="" method="POST" id="editUserForm">
            @csrf
            @method('PUT')
            
            <div style="margin-bottom: 1rem;">
                <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Full Name</label>
                <input type="text" name="name" id="edit_u_name" required style="width:100%; padding:0.6rem; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main);">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Primary Email</label>
                    <input type="email" name="email" id="edit_u_email" required style="width:100%; padding:0.6rem; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main);">
                </div>
                <div>
                    <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Phone Number</label>
                    <input type="text" name="phone_number" id="edit_u_phone" style="width:100%; padding:0.6rem; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main);">
                </div>
            </div>

            <div style="margin-bottom: 1rem; background: var(--bg-surface-elevated); padding: 1rem; border-radius: 8px; border: 1px solid var(--border-color);">
                <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem; color: var(--primary);">Change User Password</label>
                <input type="password" name="password" minlength="8" placeholder="Leave blank to keep existing password" style="width:100%; padding:0.6rem; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-surface); color:var(--text-main);">
                <span style="font-size: 0.76rem; color: var(--text-muted); margin-top: 0.3rem; display: block;">Enter a new minimum 8-character password to reset this account's password.</span>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.88rem; font-weight: 700; cursor: pointer;">
                    <input type="checkbox" name="is_super_admin" value="1" id="edit_u_super_admin">
                    <span>Grant Global Super Admin Privileges</span>
                </label>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:0.75rem;">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('editUserModal').style.display='none'">Cancel</button>
                <button type="submit" class="btn btn-primary">Save User Credentials</button>
            </div>
        </form>
    </div>
</div>
@endif

<style>
    .tab-vertical-btn {
        display: flex;
        align-items: center;
        gap: 0.65rem;
        padding: 0.65rem 1rem;
        border-radius: 8px;
        color: var(--text-muted);
        font-size: 0.85rem;
        font-weight: 600;
        background: transparent;
        border: none;
        cursor: pointer;
        transition: all 0.15s ease;
        text-align: left;
        width: 100%;
    }

    .tab-vertical-btn:hover {
        background: var(--bg-surface-elevated);
        color: var(--text-main);
    }

    .tab-vertical-btn.active {
        background: var(--primary-light);
        color: var(--primary);
    }
</style>

<script>
    function switchSettingsTab(tabId, btnEl) {
        document.querySelectorAll('.settings-tab-panel').forEach(panel => panel.style.display = 'none');
        document.querySelectorAll('.tab-vertical-btn').forEach(btn => btn.classList.remove('active'));
        
        const targetPanel = document.getElementById(tabId);
        if (targetPanel) targetPanel.style.display = 'block';
        if (btnEl) btnEl.classList.add('active');
    }

    function openEditUserModal(u) {
        document.getElementById('editUserForm').action = '/users/' + u.id;
        document.getElementById('edit_u_name').value = u.name || '';
        document.getElementById('edit_u_email').value = u.email || '';
        document.getElementById('edit_u_phone').value = u.phone_number || '';
        document.getElementById('edit_u_super_admin').checked = !!u.is_super_admin;
        document.getElementById('editUserModal').style.display = 'flex';
    }

    function addEmailField() {
        const container = document.getElementById('emailsListContainer');
        const row = document.createElement('div');
        row.className = 'email-input-row';
        row.style.display = 'flex';
        row.style.gap = '0.5rem';
        row.innerHTML = `
            <input type="email" name="emails[]" placeholder="secondary.email@domain.com" required style="flex: 1;">
            <button type="button" class="btn btn-danger" onclick="this.parentElement.remove()" style="padding: 0.6rem 0.8rem;">✕</button>
        `;
        container.appendChild(row);
    }
</script>
@endsection
