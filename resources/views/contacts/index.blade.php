@extends('layouts.app')

@section('title', 'External Contacts - PragmaTick')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem; background: var(--bg-surface); padding: 1.25rem 1.5rem; border-radius: 12px; border: 1px solid var(--border-color); box-shadow: var(--shadow-sm);">
    <div>
        <div style="display: flex; align-items: center; gap: 0.5rem;">
            <h1 style="font-size: 1.65rem; font-weight: 800;">External Contacts Directory</h1>
        </div>
        <p style="color: var(--text-muted); font-size: 0.88rem; margin-top: 0.2rem;">
            Enterprise contact management for external clients, partners, and vendors
        </p>
    </div>

    <button class="btn btn-primary" onclick="document.getElementById('createContactModal').style.display='flex'">
        + Add Contact
    </button>
</div>

<div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 12px; padding: 1.5rem; box-shadow: var(--card-shadow);">
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; font-size: 0.88rem;">
            <thead>
                <tr style="border-bottom: 1px solid var(--border-color); background: var(--bg-surface-elevated);">
                    <th style="padding: 0.75rem; text-align: left;">Contact Name</th>
                    <th style="padding: 0.75rem; text-align: left;">Company & Position</th>
                    <th style="padding: 0.75rem; text-align: left;">Phone</th>
                    <th style="padding: 0.75rem; text-align: left;">Email</th>
                    <th style="padding: 0.75rem; text-align: left;">Notes</th>
                    <th style="padding: 0.75rem; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($contacts as $c)
                    <tr style="border-bottom: 1px solid var(--border-color);">
                        <td style="padding: 0.75rem;">
                            <strong>{{ $c->name }}</strong>
                        </td>
                        <td style="padding: 0.75rem;">
                            <div>{{ $c->company ?: 'Independent' }}</div>
                            <small style="color: var(--text-muted);">{{ $c->position ?: 'N/A' }}</small>
                        </td>
                        <td style="padding: 0.75rem;">{{ $c->phone ?: 'N/A' }}</td>
                        <td style="padding: 0.75rem;">
                            <a href="mailto:{{ $c->email }}" style="color: var(--primary); text-decoration: none;">{{ $c->email }}</a>
                        </td>
                        <td style="padding: 0.75rem; max-width: 200px;">
                            <span style="color: var(--text-muted); font-size: 0.82rem;">{{ Str::limit($c->notes, 60) }}</span>
                        </td>
                        <td style="padding: 0.75rem; text-align: right;">
                            <button class="btn btn-secondary" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;" onclick="openEditContactModal({{ json_encode($c) }})">Edit</button>
                            <form action="{{ route('contacts.destroy', $c) }}" method="POST" onsubmit="return promptDelete('Contact {{ addslashes($c->name) }}', this);" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding: 2rem; text-align: center; color: var(--text-muted);">No external CRM contacts recorded.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($contacts->hasPages())
        <div style="margin-top: 1.25rem;">
            {{ $contacts->links() }}
        </div>
    @endif
</div>

<!-- Create Contact Modal -->
<div id="createContactModal" style="display: none; position: fixed; top:0; left:0; right:0; bottom:0; background: rgba(0,0,0,0.6); align-items:center; justify-content:center; z-index:900; backdrop-filter: blur(4px);">
    <div style="background: var(--bg-surface); padding: 2rem; border-radius: 14px; width: 90%; max-width: 500px; border: 1px solid var(--border-color);">
        <h3 style="margin-bottom: 1rem; font-weight: 800; color: var(--primary);">+ Add External CRM Contact</h3>
        <form action="{{ route('contacts.store') }}" method="POST">
            @csrf
            <div style="margin-bottom: 1rem;">
                <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Full Name</label>
                <input type="text" name="name" required placeholder="e.g. Sarah Connor" style="width:100%; padding:0.6rem; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main);">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Company Name</label>
                    <input type="text" name="company" placeholder="e.g. Cyberdyne Systems" style="width:100%; padding:0.6rem; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main);">
                </div>
                <div>
                    <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Position / Title</label>
                    <input type="text" name="position" placeholder="e.g. CTO" style="width:100%; padding:0.6rem; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main);">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Phone Number</label>
                    <input type="text" name="phone" placeholder="+1-555-0199" style="width:100%; padding:0.6rem; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main);">
                </div>
                <div>
                    <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Email Address</label>
                    <input type="email" name="email" required placeholder="sarah@cyberdyne.com" style="width:100%; padding:0.6rem; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main);">
                </div>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Notes / Metadata</label>
                <textarea name="notes" rows="3" style="width:100%; padding:0.6rem; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main);"></textarea>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:0.75rem;">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('createContactModal').style.display='none'">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Contact</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Contact Modal -->
<div id="editContactModal" style="display: none; position: fixed; top:0; left:0; right:0; bottom:0; background: rgba(0,0,0,0.6); align-items:center; justify-content:center; z-index:900; backdrop-filter: blur(4px);">
    <div style="background: var(--bg-surface); padding: 2rem; border-radius: 14px; width: 90%; max-width: 500px; border: 1px solid var(--border-color);">
        <h3 style="margin-bottom: 1rem; font-weight: 800; color: var(--primary);">Edit Contact</h3>
        <form action="" method="POST" id="editContactForm">
            @csrf
            @method('PUT')
            
            <div style="margin-bottom: 1rem;">
                <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Full Name</label>
                <input type="text" name="name" id="cnt_name" required style="width:100%; padding:0.6rem; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main);">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Company Name</label>
                    <input type="text" name="company" id="cnt_company" style="width:100%; padding:0.6rem; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main);">
                </div>
                <div>
                    <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Position / Title</label>
                    <input type="text" name="position" id="cnt_position" style="width:100%; padding:0.6rem; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main);">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Phone Number</label>
                    <input type="text" name="phone" id="cnt_phone" style="width:100%; padding:0.6rem; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main);">
                </div>
                <div>
                    <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Email Address</label>
                    <input type="email" name="email" id="cnt_email" required style="width:100%; padding:0.6rem; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main);">
                </div>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Notes / Metadata</label>
                <textarea name="notes" id="cnt_notes" rows="3" style="width:100%; padding:0.6rem; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main);"></textarea>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:0.75rem;">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('editContactModal').style.display='none'">Cancel</button>
                <button type="submit" class="btn btn-primary">Update Contact</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditContactModal(contact) {
        document.getElementById('editContactForm').action = '/contacts/' + contact.id;
        document.getElementById('cnt_name').value = contact.name || '';
        document.getElementById('cnt_company').value = contact.company || '';
        document.getElementById('cnt_position').value = contact.position || '';
        document.getElementById('cnt_phone').value = contact.phone || '';
        document.getElementById('cnt_email').value = contact.email || '';
        document.getElementById('cnt_notes').value = contact.notes || '';
        document.getElementById('editContactModal').style.display = 'flex';
    }
</script>
@endsection
