@extends('layouts.app')

@section('title', ($page->exists ? 'Edit ' . $page->title : 'New Wiki Page') . ' - PragmaTick')

@section('content')
<!-- Mermaid.js, Marked.js & DOMPurify CDN -->
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dompurify@3.1.6/dist/purify.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/mermaid/dist/mermaid.min.js"></script>

<div style="margin-bottom: 1.5rem;">
    <a href="{{ route('wikis.showBook', $chapter->book) }}" style="color: var(--primary); text-decoration: none; font-weight: 600; font-size: 0.88rem;">
        &larr; Back to {{ $chapter->book->title }} (Chapter: {{ $chapter->title }})
    </a>

    <h1 style="font-size: 1.75rem; font-weight: 800; margin-top: 0.5rem;">
        {{ $page->exists ? 'Edit Page: ' . $page->title : 'New Wiki Page in Chapter: ' . $chapter->title }}
    </h1>
</div>

<form action="{{ $page->exists ? route('wikis.pages.update', $page) : route('wikis.pages.store', $chapter) }}" method="POST" onsubmit="syncOnSubmit()">
    @csrf
    @if($page->exists)
        @method('PUT')
    @endif

    <div style="margin-bottom: 1.25rem;">
        <label style="display:block; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.3rem;">Page Title</label>
        <input type="text" name="title" value="{{ old('title', $page->title) }}" placeholder="e.g. Real-Time Telemetry Pipeline & Architecture" required style="width:100%; padding:0.6rem; border-radius:6px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main); font-size: 1rem; font-weight: 700;">
    </div>

<style>
    .editor-toggle-pill-track {
        display: inline-flex;
        align-items: center;
        background: var(--bg-surface-elevated);
        padding: 4px;
        border-radius: 30px;
        border: 1px solid var(--border-color);
        box-shadow: inset 0 1px 3px rgba(0,0,0,0.12);
        gap: 4px;
    }

    .editor-toggle-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.45rem 1rem;
        border-radius: 24px;
        border: none;
        background: transparent;
        color: var(--text-muted);
        font-size: 0.82rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        user-select: none;
    }

    .editor-toggle-btn:hover {
        color: var(--text-main);
        background: rgba(255, 255, 255, 0.05);
    }

    .editor-toggle-btn.active {
        background: var(--primary);
        color: #ffffff;
        box-shadow: 0 2px 10px rgba(32, 178, 170, 0.3);
        transform: translateY(-1px);
    }

    .editor-toggle-btn svg {
        transition: transform 0.25s ease;
    }

    .editor-toggle-btn.active svg {
        transform: scale(1.1);
    }
</style>

    <!-- Interactive Dual Editor Mode Switcher Bar -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 0.75rem;">
        <div style="display: flex; align-items: center; gap: 0.75rem;">
            <span style="font-size: 0.85rem; font-weight: 700; color: var(--text-muted);">Editor Mode:</span>
            <div class="editor-toggle-pill-track">
                <button type="button" class="editor-toggle-btn active" id="btnMarkdownMode" onclick="setEditorMode('markdown')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m16 18 6-6-6-6"/><path d="m8 6-6 6 6 6"/></svg>
                    <span>Markdown & Live Preview</span>
                </button>
                <button type="button" class="editor-toggle-btn" id="btnWysiwygMode" onclick="setEditorMode('wysiwyg')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                    <span>WYSIWYG Rich Text (Code Supported)</span>
                </button>
            </div>
        </div>
        <div style="font-size: 0.78rem; color: var(--text-muted); font-weight: 600;" id="editorModeHint">
            Markdown Mode: Live Mermaid.js diagram & markdown syntax parser active.
        </div>
    </div>

    <!-- Hidden Form Input for Form Submission -->
    <input type="hidden" name="content" id="hiddenContentInput" value="{{ old('content', $page->content ?? '') }}">

    <!-- Mode 1: Dual Panel Markdown Editor & Live Preview -->
    <div id="markdownModeContainer" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
        <!-- Left Panel: Markdown Raw Editor -->
        <div>
            <div style="margin-bottom: 0.4rem;">
                <label style="font-size: 0.85rem; font-weight: 700;">Markdown Editor</label>
            </div>
            <textarea id="markdownEditor" rows="22" placeholder="Write markdown documentation here..." style="width:100%; padding:0.85rem; border-radius:8px; border:1px solid var(--border-color); background:var(--bg-surface-elevated); color:var(--text-main); font-family: monospace; font-size: 0.88rem; line-height: 1.5;">{{ old('content', $page->content ?? '') }}</textarea>
        </div>

        <!-- Right Panel: Real-Time Live Preview -->
        <div>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.4rem;">
                <label style="font-size: 0.85rem; font-weight: 700; color: var(--primary);">Real-Time Live Preview (Mermaid.js)</label>
                <span class="tag tag-cyan">LIVE RENDER ACTIVE</span>
            </div>
            <div id="livePreviewPanel" style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 8px; padding: 1rem; height: 490px; overflow-y: auto; box-shadow: var(--shadow-sm); font-size: 0.9rem; line-height: 1.6;">
                <!-- Live Output -->
            </div>
        </div>
    </div>

    <!-- Mode 2: WYSIWYG Rich Text Editor with Code Block Configuration -->
    <div id="wysiwygModeContainer" style="display: none; margin-bottom: 1.5rem;">
        <!-- WYSIWYG Formatting Toolbar -->
        <div style="background: var(--bg-surface-elevated); border: 1px solid var(--border-color); border-bottom: none; border-top-left-radius: 10px; border-top-right-radius: 10px; padding: 0.6rem 0.85rem; display: flex; flex-wrap: wrap; gap: 0.4rem; align-items: center;">
            <button type="button" class="btn btn-secondary" onclick="execCmd('bold')" style="font-size: 0.78rem; padding: 0.25rem 0.55rem; font-weight: 800;">B</button>
            <button type="button" class="btn btn-secondary" onclick="execCmd('italic')" style="font-size: 0.78rem; padding: 0.25rem 0.55rem; font-style: italic;">I</button>
            <button type="button" class="btn btn-secondary" onclick="execCmd('underline')" style="font-size: 0.78rem; padding: 0.25rem 0.55rem; text-decoration: underline;">U</button>
            <button type="button" class="btn btn-secondary" onclick="execCmd('strikeThrough')" style="font-size: 0.78rem; padding: 0.25rem 0.55rem; text-decoration: line-through;">S</button>
            
            <span style="width: 1px; height: 18px; background: var(--border-color); margin: 0 0.3rem;"></span>

            <button type="button" class="btn btn-secondary" onclick="execCmd('formatBlock', '<h1>')" style="font-size: 0.78rem; padding: 0.25rem 0.55rem;">H1</button>
            <button type="button" class="btn btn-secondary" onclick="execCmd('formatBlock', '<h2>')" style="font-size: 0.78rem; padding: 0.25rem 0.55rem;">H2</button>
            <button type="button" class="btn btn-secondary" onclick="execCmd('formatBlock', '<h3>')" style="font-size: 0.78rem; padding: 0.25rem 0.55rem;">H3</button>

            <span style="width: 1px; height: 18px; background: var(--border-color); margin: 0 0.3rem;"></span>

            <button type="button" class="btn btn-secondary" onclick="execCmd('insertUnorderedList')" style="font-size: 0.78rem; padding: 0.25rem 0.55rem;">• Bullet List</button>
            <button type="button" class="btn btn-secondary" onclick="execCmd('insertOrderedList')" style="font-size: 0.78rem; padding: 0.25rem 0.55rem;">1. Numbered List</button>

            <span style="width: 1px; height: 18px; background: var(--border-color); margin: 0 0.3rem;"></span>

            <!-- Code Support Tools -->
            <button type="button" class="btn btn-primary" onclick="insertWysiwygCodeBlock()" style="font-size: 0.78rem; padding: 0.25rem 0.6rem; font-family: monospace;">
                &lt;/&gt; Insert Code Block
            </button>
            <button type="button" class="btn btn-secondary" onclick="execCmd('formatBlock', '<blockquote>')" style="font-size: 0.78rem; padding: 0.25rem 0.55rem;">
                &ldquo; Quote
            </button>
        </div>

        <!-- Editable WYSIWYG Content Area -->
        <div id="wysiwygEditor" contenteditable="true" style="background: var(--bg-surface); border: 1px solid var(--border-color); border-bottom-left-radius: 10px; border-bottom-right-radius: 10px; padding: 1.25rem; min-height: 440px; color: var(--text-main); font-size: 0.94rem; line-height: 1.6; outline: none;">
            <!-- WYSIWYG Editable HTML Body -->
        </div>
    </div>

    <div style="display: flex; justify-content: flex-end; gap: 1rem; border-top: 1px solid var(--border-color); padding-top: 1.25rem;">
        <a href="{{ $page->exists ? route('wikis.showPage', $page) : route('wikis.showBook', $chapter->book) }}" class="btn btn-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary">Publish Wiki Page</button>
    </div>
</form>

<script>
    let currentMode = 'markdown';
    const markdownEditor = document.getElementById('markdownEditor');
    const livePreviewPanel = document.getElementById('livePreviewPanel');
    const wysiwygEditor = document.getElementById('wysiwygEditor');
    const hiddenInput = document.getElementById('hiddenContentInput');

    function setEditorMode(mode) {
        currentMode = mode;
        const markdownContainer = document.getElementById('markdownModeContainer');
        const wysiwygContainer = document.getElementById('wysiwygModeContainer');
        const btnMarkdown = document.getElementById('btnMarkdownMode');
        const btnWysiwyg = document.getElementById('btnWysiwygMode');
        const hint = document.getElementById('editorModeHint');

        if (mode === 'markdown') {
            syncWysiwygToMarkdown();
            markdownContainer.style.display = 'grid';
            wysiwygContainer.style.display = 'none';
            btnMarkdown.classList.add('active');
            btnWysiwyg.classList.remove('active');
            hint.textContent = 'Markdown Mode: Live Mermaid.js diagram & markdown syntax parser active.';
            updateLivePreview();
        } else {
            syncMarkdownToWysiwyg();
            markdownContainer.style.display = 'none';
            wysiwygContainer.style.display = 'block';
            btnMarkdown.classList.remove('active');
            btnWysiwyg.classList.add('active');
            hint.textContent = 'WYSIWYG Mode: Rich text formatting with Code Block </ > configuration enabled.';
        }
    }

    function syncMarkdownToWysiwyg() {
        const markdownVal = markdownEditor.value;
        if (window.marked) {
            const rawHtml = marked.parse(markdownVal);
            wysiwygEditor.innerHTML = window.DOMPurify ? DOMPurify.sanitize(rawHtml) : rawHtml;
        } else {
            wysiwygEditor.textContent = markdownVal;
        }
    }

    function syncWysiwygToMarkdown() {
        const htmlVal = wysiwygEditor.innerHTML;
        // Simple HTML-to-Markdown convert or keep HTML for rich text rendering
        markdownEditor.value = htmlVal;
    }

    function syncOnSubmit() {
        if (currentMode === 'wysiwyg') {
            hiddenInput.value = wysiwygEditor.innerHTML;
        } else {
            hiddenInput.value = markdownEditor.value;
        }
    }

    function execCmd(command, value = null) {
        document.execCommand(command, false, value);
    }

    function insertWysiwygCodeBlock() {
        const sel = window.getSelection();
        let selectedText = '// Enter code snippet or diagram here...';
        if (sel && sel.rangeCount > 0 && sel.toString().trim().length > 0) {
            selectedText = sel.toString();
        }
        
        const pre = document.createElement('pre');
        const code = document.createElement('code');
        code.textContent = selectedText;
        pre.appendChild(code);
        pre.style.background = 'var(--bg-surface-elevated)';
        pre.style.border = '1px solid var(--border-color)';
        pre.style.padding = '0.75rem 1rem';
        pre.style.borderRadius = '6px';
        pre.style.fontFamily = 'monospace';
        pre.style.fontSize = '0.86rem';
        pre.style.margin = '0.75rem 0';
        pre.style.color = 'var(--primary)';

        const range = sel.getRangeAt(0);
        range.deleteContents();
        range.insertNode(pre);
    }

    function updateLivePreview() {
        const val = markdownEditor.value;
        if (window.marked) {
            const rawHtml = marked.parse(val);
            livePreviewPanel.innerHTML = window.DOMPurify ? DOMPurify.sanitize(rawHtml) : rawHtml;
        } else {
            livePreviewPanel.textContent = val;
        }

        const codeBlocks = livePreviewPanel.querySelectorAll('pre code.language-mermaid, pre code.lang-mermaid');
        codeBlocks.forEach((codeBlock) => {
            const preEl = codeBlock.parentElement;
            const mDiv = document.createElement('div');
            mDiv.className = 'mermaid';
            mDiv.textContent = codeBlock.textContent;
            preEl.parentNode.replaceChild(mDiv, preEl);
        });

        const allPre = livePreviewPanel.querySelectorAll('pre');
        allPre.forEach((pre) => {
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
            try {
                mermaid.initialize({
                    startOnLoad: false,
                    theme: document.documentElement.getAttribute('data-theme') === 'dark' ? 'dark' : 'default',
                    securityLevel: 'loose'
                });
                mermaid.init(undefined, livePreviewPanel.querySelectorAll('.mermaid'));
            } catch(e) {
                console.log('Mermaid parsing preview...', e);
            }
        }
    }

    markdownEditor.addEventListener('input', updateLivePreview);
    document.addEventListener('DOMContentLoaded', updateLivePreview);
</script>
@endsection
