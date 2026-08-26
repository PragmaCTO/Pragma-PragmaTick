@props(['title', 'value', 'subtitleValue' => null, 'description', 'color' => 'primary'])

<div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 12px; padding: 1.25rem; box-shadow: var(--card-shadow);">
    <div style="font-size: 0.75rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.4rem;">
        {{ $title }}
    </div>
    <div style="font-size: 1.85rem; font-weight: 800; color: var(--{{ $color }});">
        {{ $value }}
        @if($subtitleValue)
            <span style="font-size: 0.95rem; font-weight: 600; color: var(--text-muted);">/ {{ $subtitleValue }}</span>
        @endif
    </div>
    <div style="font-size: 0.78rem; color: var(--text-muted); margin-top: 0.3rem;">
        {{ $description }}
    </div>
</div>
