@extends('layouts.app')

@section('title', $page->title . ' - Wiki Documentation Workspace')

@section('content')
<!-- Mermaid.js & Marked.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/mermaid/dist/mermaid.min.js"></script>

<!-- Combined Top Header Card with Document Info & Timestamps -->
<div class="page-header-bar" style="background: var(--bg-surface); padding: 1.25rem 1.5rem; border-radius: 12px; border: 1px solid var(--border-color); box-shadow: var(--card-shadow); margin-bottom: 1.5rem;">
    <div>
        <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 0.35rem;">
            <a href="{{ route('wikis.showBook', $page->chapter->book) }}" style="color: var(--primary); text-decoration: none; font-size: 0.85rem; font-weight: 700;">
                {{ $page->chapter->book->title }}
            </a>
            <span style="color: var(--text-muted); font-size: 0.8rem;">&rsaquo;</span>
            <span style="color: var(--text-muted); font-size: 0.85rem; font-weight: 600;">{{ $page->chapter->title }}</span>
        </div>
        <h1 class="page-header-title" style="font-size: 1.6rem; font-weight: 800; margin-bottom: 0.6rem; color: var(--text-main);">
            {{ $page->title }}
        </h1>
        
        <!-- Combined Meta Badge Bar -->
        <div style="display: flex; align-items: center; gap: 1.25rem; flex-wrap: wrap; font-size: 0.82rem; color: var(--text-muted);">
            <span>Author: <strong style="color: var(--text-main);">{{ $page->author->name }}</strong></span>
            <span>Created: <strong style="color: var(--text-main);">{{ $page->created_at->format('M d, Y') }}</strong></span>
            <span>Last Updated: <strong style="color: var(--primary);">{{ $page->updated_at->format('M d, Y H:i') }} ({{ $page->updated_at->diffForHumans() }})</strong></span>
            <span>Status: <strong style="color: var(--primary);">Active Document</strong></span>
        </div>
    </div>

    <div class="page-header-actions">
        <a href="{{ route('wikis.showBook', $page->chapter->book) }}" class="btn btn-secondary">
            &larr; Back to Book
        </a>
        @can('update', $page->chapter->book)
            <a href="{{ route('wikis.pages.edit', $page) }}" class="btn btn-primary">Edit Page</a>
        @endcan
        @can('delete', $page->chapter->book)
            <form action="{{ route('wikis.destroyPage', $page) }}" method="POST" onsubmit="return promptDelete('Wiki Page {{ addslashes($page->title) }}', this);" style="margin:0;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
        @endcan
    </div>
</div>

<style>
    /* Prominent TOC Separator Line */
    .toc-separator-sidebar {
        border-right: 2px solid var(--border-color);
        padding-right: 1.75rem;
        padding-top: 0.5rem;
        position: sticky;
        top: 1.5rem;
        max-height: calc(100vh - 3rem);
        overflow-y: auto;
    }

    /* Dark Mode & High-Contrast Document Reader Typography */
    .markdown-body,
    .markdown-body p,
    .markdown-body li,
    .markdown-body span,
    .markdown-body div,
    .markdown-body strong,
    .markdown-body em,
    .markdown-body td,
    .markdown-body th {
        color: var(--text-main) !important;
    }

    .markdown-body h1,
    .markdown-body h2,
    .markdown-body h3,
    .markdown-body h4,
    .markdown-body h5,
    .markdown-body h6 {
        color: var(--text-main) !important;
        border-bottom-color: var(--border-color) !important;
        margin-top: 1.25rem;
        margin-bottom: 0.75rem;
        font-weight: 800;
    }

    .markdown-body a {
        color: var(--primary) !important;
        text-decoration: underline;
    }

    .markdown-body code {
        background: var(--bg-surface-elevated) !important;
        color: var(--primary) !important;
        padding: 0.2rem 0.4rem;
        border-radius: 4px;
        font-family: monospace;
        font-size: 0.88em;
    }

    .markdown-body pre {
        background: var(--bg-surface-elevated) !important;
        border: 1px solid var(--border-color) !important;
        padding: 1rem !important;
        border-radius: 8px !important;
        overflow-x: auto;
    }

    .markdown-body pre code {
        background: transparent !important;
        padding: 0 !important;
    }

    .markdown-body blockquote {
        border-left: 3.5px solid var(--primary) !important;
        color: var(--text-muted) !important;
        padding-left: 1rem !important;
        margin: 1rem 0 !important;
    }

    /* Target Mermaid SVG Text Visibility & Node Styling in Dark Mode */
    .mermaid {
        margin: 1.5rem 0;
        text-align: center;
    }

    .mermaid svg text,
    .mermaid svg text *,
    .mermaid svg tspan,
    .mermaid svg .nodeText,
    .mermaid svg .nodeText *,
    .mermaid svg .label,
    .mermaid svg .label *,
    .mermaid svg .actor,
    .mermaid svg .actor *,
    .mermaid svg .messageText,
    .mermaid svg .messageText *,
    .mermaid svg .noteText,
    .mermaid svg .noteText * {
        fill: var(--text-main) !important;
        color: var(--text-main) !important;
        font-family: inherit !important;
    }

    .mermaid svg .node rect,
    .mermaid svg .node circle,
    .mermaid svg .node polygon,
    .mermaid svg .node path,
    .mermaid svg .actor {
        fill: var(--bg-surface-elevated) !important;
        stroke: var(--border-color) !important;
        stroke-width: 1.5px !important;
    }

    .mermaid svg .edgePath .path,
    .mermaid svg .flowchart-link,
    .mermaid svg .messageLine0,
    .mermaid svg .messageLine1 {
        stroke: var(--primary) !important;
        stroke-width: 1.5px !important;
    }

    .mermaid svg .labelBox,
    .mermaid svg .edgeLabel {
        fill: var(--bg-surface-elevated) !important;
        background-color: var(--bg-surface-elevated) !important;
        color: var(--text-main) !important;
    }
</style>

<!-- 2-Column Expanded Wiki Reading Workspace (Sticky TOC Sidebar + Full-Right Reader) -->
<div style="display: grid; grid-template-columns: 310px minmax(0, 1fr); gap: 2.25rem; align-items: start; width: 100%;">
    
    <!-- Column 1: Traditional Table of Contents (TOC) Sticky Sidebar with Prominent Right Separator Line -->
    <div class="toc-separator-sidebar">
        <div style="font-size: 0.76rem; font-weight: 800; text-transform: uppercase; color: var(--text-muted); letter-spacing: 0.06em; margin-bottom: 1rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem;">
            Table of Contents
        </div>
        <div style="display: flex; flex-direction: column; gap: 1.1rem;">
            @foreach($page->chapter->book->chapters as $cIndex => $chap)
                <div>
                    <div style="font-size: 0.82rem; font-weight: 800; color: var(--text-main); margin-bottom: 0.4rem; display: flex; align-items: flex-start; gap: 0.35rem; line-height: 1.4;">
                        <span style="color: var(--primary); font-size: 0.76rem; font-family: monospace; flex-shrink: 0; margin-top: 0.05rem;">{{ $cIndex + 1 }}.0</span>
                        <span style="word-break: break-word;">{{ $chap->title }}</span>
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 0.3rem; padding-left: 0.75rem; border-left: 1.5px dashed var(--border-color); margin-left: 0.25rem;">
                        @foreach($chap->pages as $pIndex => $pItem)
                            @php $isActive = $pItem->id === $page->id; @endphp
                            <a href="{{ route('wikis.showPage', $pItem) }}" style="font-size: 0.78rem; text-decoration: none; padding: 0.35rem 0.55rem; border-radius: 6px; display: block; color: {{ $isActive ? 'var(--primary)' : 'var(--text-muted)' }}; background: {{ $isActive ? 'rgba(32, 178, 170, 0.12)' : 'transparent' }}; font-weight: {{ $isActive ? '700' : '500' }}; transition: all 0.15s ease; line-height: 1.4; word-break: break-word;">
                                <span style="opacity: 0.6; font-size: 0.74rem; font-family: monospace; margin-right: 0.25rem;">{{ $cIndex + 1 }}.{{ $pIndex + 1 }}</span>
                                {{ $pItem->title }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Column 2: Full-Width Borderless Document Reader (Shifted Full Right) -->
    <div style="padding: 0.5rem 0; min-height: 500px; width: 100%; min-width: 0; overflow-x: auto;">
        <div id="rawMarkdownContent" style="display: none;">{{ $page->content }}</div>
        
        <div id="renderedContentArea" class="markdown-body" style="font-size: 1rem; line-height: 1.8; color: var(--text-main); width: 100%;">
            <!-- Rendered Output -->
        </div>
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const rawContent = document.getElementById('rawMarkdownContent').textContent;
        const targetEl = document.getElementById('renderedContentArea');

        if (window.marked) {
            targetEl.innerHTML = marked.parse(rawContent);
        } else {
            targetEl.innerHTML = rawContent.replace(/\n/g, '<br>');
        }

        const codeBlocks = targetEl.querySelectorAll('pre code.language-mermaid, pre code.lang-mermaid');
        codeBlocks.forEach((codeBlock) => {
            const preEl = codeBlock.parentElement;
            const mermaidDiv = document.createElement('div');
            mermaidDiv.className = 'mermaid';
            mermaidDiv.textContent = codeBlock.textContent;
            preEl.parentNode.replaceChild(mermaidDiv, preEl);
        });

        const allPreBlocks = targetEl.querySelectorAll('pre');
        allPreBlocks.forEach((pre) => {
            if (pre.textContent.trim().startsWith('sequenceDiagram') || 
                pre.textContent.trim().startsWith('graph') || 
                pre.textContent.trim().startsWith('flowchart') ||
                pre.textContent.trim().startsWith('classDiagram')) {
                const mDiv = document.createElement('div');
                mDiv.className = 'mermaid';
                mDiv.textContent = pre.textContent.trim();
                pre.parentNode.replaceChild(mDiv, pre);
            }
        });

        if (window.mermaid) {
            const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
            mermaid.initialize({
                startOnLoad: false,
                theme: isDark ? 'dark' : 'default',
                themeVariables: isDark ? {
                    darkMode: true,
                    background: 'transparent',
                    primaryColor: 'rgba(32, 178, 170, 0.25)',
                    primaryTextColor: '#f3f4f6',
                    primaryBorderColor: '#20b2aa',
                    lineColor: '#20b2aa',
                    textColor: '#f3f4f6',
                    nodeBkg: 'rgba(30, 41, 59, 0.9)',
                    nodeBorder: '#334155',
                    clusterBkg: 'rgba(15, 23, 42, 0.7)',
                    clusterBorder: '#475569',
                    defaultLinkColor: '#20b2aa',
                    titleColor: '#f3f4f6',
                    edgeLabelBackground: 'rgba(15, 23, 42, 0.9)'
                } : {},
                securityLevel: 'loose'
            });
            mermaid.init(undefined, document.querySelectorAll('.mermaid'));
        }
    });
</script>
@endsection
