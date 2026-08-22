@extends('layouts.app')

@section('title', 'System Recovery & Activity Audit - PragmaTick')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem; background: var(--bg-surface); padding: 1.25rem 1.5rem; border-radius: 12px; border: 1px solid var(--border-color); box-shadow: var(--shadow-sm);">
    <div>
        <div style="display: flex; align-items: center; gap: 0.5rem;">
            <h1 style="font-size: 1.65rem; font-weight: 800;">System Recovery & Activity Audit Console</h1>
        </div>
        <p style="color: var(--text-muted); font-size: 0.88rem; margin-top: 0.2rem;">
            System activity audit logs & physical soft-deleted record restoration engine
        </p>
    </div>
</div>

<!-- Section 1: Soft-Deleted Records Browser (Trash Bin) -->
<div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 12px; padding: 1.5rem; box-shadow: var(--card-shadow); margin-bottom: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.6rem;">
        <div>
            <h3 style="font-size: 1.15rem; font-weight: 700; color: var(--primary);">System Trash Bin (Soft-Deleted Records)</h3>
            <p style="font-size: 0.82rem; color: var(--text-muted);">Physical restoration button reverts deleted records back to active state.</p>
        </div>
        <strong style="font-size: 0.88rem; font-weight: 700; color: var(--primary);">{{ count($deletedRecords) }} Records Pending Restoration</strong>
    </div>

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; font-size: 0.88rem;">
            <thead>
                <tr style="border-bottom: 1px solid var(--border-color); background: var(--bg-surface-elevated);">
                    <th style="padding: 0.75rem; text-align: left;">Resource Type</th>
                    <th style="padding: 0.75rem; text-align: left;">Record Identity</th>
                    <th style="padding: 0.75rem; text-align: left;">Deleted Datetime</th>
                    <th style="padding: 0.75rem; text-align: right;">Restore Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($deletedRecords as $item)
                    <tr style="border-bottom: 1px solid var(--border-color);">
                        <td style="padding: 0.75rem;">
                            <strong style="font-weight: 700; color: var(--text-main); font-size: 0.82rem; text-transform: uppercase;">{{ $item['type'] }}</strong>
                        </td>
                        <td style="padding: 0.75rem;">
                            <strong>{{ $item['name'] }}</strong>
                            <small style="color: var(--text-muted); display: block;">ID: #{{ $item['id'] }}</small>
                        </td>
                        <td style="padding: 0.75rem; color: var(--text-muted);">
                            {{ $item['deleted_at'] ? \Carbon\Carbon::parse($item['deleted_at'])->diffForHumans() : 'Unknown' }}
                        </td>
                        <td style="padding: 0.75rem; text-align: right;">
                            <form action="{{ route('recovery.restore') }}" method="POST" style="margin: 0;">
                                @csrf
                                <input type="hidden" name="type" value="{{ $item['type'] }}">
                                <input type="hidden" name="id" value="{{ $item['id'] }}">
                                <button type="submit" class="btn btn-primary" style="font-size: 0.75rem; padding: 0.3rem 0.65rem;">
                                    Restore Record
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="padding: 2rem; text-align: center; color: var(--text-muted);">
                            No soft-deleted records found. System trash bin is empty.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Section 2: Activity Logs Audit Inspector -->
<div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 12px; padding: 1.5rem; box-shadow: var(--card-shadow);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.6rem;">
        <div>
            <h3 style="font-size: 1.15rem; font-weight: 700;">System Activity Logs Audit</h3>
            <p style="font-size: 0.82rem; color: var(--text-muted);">Polymorphic audit logging tracking user operations</p>
        </div>
    </div>

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; font-size: 0.88rem;">
            <thead>
                <tr style="border-bottom: 1px solid var(--border-color); background: var(--bg-surface-elevated);">
                    <th style="padding: 0.75rem; text-align: left;">Timestamp</th>
                    <th style="padding: 0.75rem; text-align: left;">User Account</th>
                    <th style="padding: 0.75rem; text-align: left;">Action Key</th>
                    <th style="padding: 0.75rem; text-align: left;">Description</th>
                    <th style="padding: 0.75rem; text-align: left;">Subject Model</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr style="border-bottom: 1px solid var(--border-color);">
                        <td style="padding: 0.75rem; color: var(--text-muted); font-family: monospace; font-size: 0.8rem;">
                            {{ $log->created_at->format('Y-m-d H:i:s') }}
                        </td>
                        <td style="padding: 0.75rem;">
                            <strong>{{ $log->user ? $log->user->name : 'System/Guest' }}</strong>
                        </td>
                        <td style="padding: 0.75rem;">
                            @if(str_contains(strtolower($log->action), 'delete') || str_contains(strtolower($log->action), 'destroy'))
                                <strong style="font-weight: 700; color: var(--accent-rose); font-size: 0.84rem;">{{ $log->action }}</strong>
                            @else
                                <strong style="font-weight: 700; color: var(--primary); font-size: 0.84rem;">{{ $log->action }}</strong>
                            @endif
                        </td>
                        <td style="padding: 0.75rem; color: var(--text-main);">
                            {{ $log->description }}
                        </td>
                        <td style="padding: 0.75rem; color: var(--text-muted); font-size: 0.8rem;">
                            {{ $log->subject_type ? class_basename($log->subject_type) . ' #' . $log->subject_id : 'N/A' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding: 2rem; text-align: center; color: var(--text-muted);">No activity logs recorded yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
