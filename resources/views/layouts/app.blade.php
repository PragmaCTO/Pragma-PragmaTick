<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'PragmaTick Workspace')</title>

    <!-- Google Fonts: Instrument Sans & JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..700;1,400..700&family=JetBrains+Mono:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- jQuery & Select2 CDNs -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            /* Ultra-Modern Light Theme Palette */
            --bg-page: #fcfcfd;
            --bg-surface: #ffffff;
            --bg-surface-elevated: #f8fafc;
            --bg-topbar: rgba(255, 255, 255, 0.88);
            --bg-sidebar: #fcfcfd;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --border-hover: #cbd5e1;
            
            --primary: #0e7490;
            --primary-hover: #0891b2;
            --primary-light: rgba(14, 116, 144, 0.1);
            --primary-dark: #155e75;

            --accent-green: #10b981;
            --accent-amber: #f59e0b;
            --accent-rose: #ef4444;

            /* Multi-layer Shadow Tokens */
            --shadow-xs: 0 1px 2px rgba(0, 0, 0, 0.04);
            --shadow-sm: 0 1px 3px 0 rgba(0, 0, 0, 0.05), 0 1px 2px -1px rgba(0, 0, 0, 0.03);
            --shadow-md: 0 4px 8px -1px rgba(0, 0, 0, 0.07), 0 2px 4px -2px rgba(0, 0, 0, 0.04);
            --shadow-lg: 0 12px 24px -4px rgba(0, 0, 0, 0.09), 0 4px 8px -2px rgba(0, 0, 0, 0.03);
            --card-shadow: var(--shadow-sm);

            --transition-smooth: 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        [data-theme="dark"] {
            /* Dark Cyan Command Center Palette */
            --bg-page: #081014;
            --bg-surface: #101c24;
            --bg-surface-elevated: #162430;
            --bg-topbar: rgba(12, 22, 29, 0.88);
            --bg-sidebar: #0e1921;
            --text-main: #f1f5f9;
            --text-muted: #94a3b8;
            --border-color: #1e3240;
            --border-hover: #2a4456;
            
            --primary: #06b6d4;
            --primary-hover: #0891b2;
            --primary-light: rgba(6, 182, 212, 0.15);
            --primary-dark: #0e7490;

            --accent-green: #10b981;
            --accent-amber: #f59e0b;
            --accent-rose: #f43f5e;

            --shadow-xs: 0 1px 2px rgba(0, 0, 0, 0.2);
            --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.3);
            --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.4);
            --shadow-lg: 0 12px 28px rgba(0, 0, 0, 0.5);
            --card-shadow: var(--shadow-md);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: var(--bg-page);
            color: var(--text-main);
            font-family: 'Instrument Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            font-size: 15px;
            line-height: 1.55;
            letter-spacing: -0.011em;
            -webkit-font-smoothing: antialiased;
            min-height: 100vh;
        }

        /* Glassmorphism Fixed Top Navbar */
        header.topbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 58px;
            background: var(--bg-topbar);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 1.5rem;
            z-index: 200;
            transition: background-color var(--transition-smooth), border-color var(--transition-smooth);
        }

        .topbar-brand {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            text-decoration: none;
            color: var(--text-main);
            font-weight: 800;
            font-size: 1.15rem;
            letter-spacing: -0.035em;
        }

        .brand-dot {
            width: 9px;
            height: 9px;
            background: linear-gradient(135deg, var(--primary) 0%, #06b6d4 100%);
            border-radius: 3px;
            box-shadow: 0 0 8px var(--primary);
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-avatar-badge {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary) 0%, #6366f1 100%);
            color: #ffffff;
            font-weight: 700;
            font-size: 0.72rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Fixed Sidebar Navigation */
        aside.sidebar {
            width: 240px;
            background: var(--bg-sidebar);
            border-right: 1px solid var(--border-color);
            position: fixed;
            top: 58px;
            bottom: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            z-index: 100;
            overflow-y: auto;
            transition: background-color var(--transition-smooth), border-color var(--transition-smooth);
        }

        .sidebar-nav {
            padding: 1rem 0;
        }

        .nav-group-title {
            padding: 0.55rem 1.25rem;
            margin: 0.85rem 0 0.35rem 0;
            font-size: 0.68rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #0891b2;
            background: rgba(6, 182, 212, 0.16);
            border-top: 1px solid rgba(6, 182, 212, 0.22);
            border-bottom: 1px solid rgba(6, 182, 212, 0.22);
            display: block;
            width: 100%;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            padding: 0.6rem 1.25rem;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
            transition: all var(--transition-smooth);
            border-left: 3px solid transparent;
        }

        .nav-link:hover {
            color: var(--text-main);
            background: var(--bg-surface-elevated);
        }

        .nav-link.active {
            color: var(--primary);
            background: var(--primary-light);
            border-left-color: var(--primary);
        }

        .nav-link svg {
            width: 16px;
            height: 16px;
            stroke-width: 2;
            flex-shrink: 0;
        }

        .sidebar-footer {
            padding: 1rem 1.25rem;
            border-top: 1px solid var(--border-color);
        }

        /* Main Workspace Container */
        main.main-workspace {
            margin-left: 240px;
            margin-top: 58px;
            padding: 2rem;
            min-height: calc(100vh - 58px);
            width: calc(100% - 240px);
        }

        /* Universal Standardized Page Header Bar */
        .page-header-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
            background: var(--bg-surface);
            padding: 1.25rem 1.5rem;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-sm);
        }

        .page-header-title {
            font-size: 1.65rem;
            font-weight: 800;
            letter-spacing: -0.025em;
            color: var(--text-main);
        }

        .page-header-subtext {
            color: var(--text-muted);
            font-size: 0.88rem;
            margin-top: 0.2rem;
        }

        .page-header-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        /* Modern Form Inputs & Select Elements */
        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="date"],
        input[type="datetime-local"],
        textarea,
        select {
            background-color: var(--bg-surface-elevated);
            color: var(--text-main);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 0.55rem 0.85rem;
            font-family: inherit;
            font-size: 0.86rem;
            font-weight: 500;
            outline: none;
            transition: all var(--transition-smooth);
            box-shadow: var(--shadow-xs);
        }

        select:not([multiple]), .persona-dropdown {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            padding-right: 2.2rem;
            cursor: pointer;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.65rem center;
            background-size: 0.9rem;
        }

        select[multiple] {
            padding: 0.5rem;
            height: auto;
            min-height: 80px;
        }

        input:hover, textarea:hover, select:hover, .persona-dropdown:hover {
            border-color: var(--border-hover);
            background-color: var(--bg-surface);
        }

        input:focus, textarea:focus, select:focus, .persona-dropdown:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-light);
            background-color: var(--bg-surface);
        }

        select option {
            background-color: var(--bg-surface);
            color: var(--text-main);
            padding: 0.5rem;
            font-size: 0.85rem;
        }

        /* Command Center Minimalist Tags */
        .tag {
            display: inline-flex;
            align-items: center;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.72rem;
            font-weight: 700;
            padding: 0.18rem 0.5rem;
            border-radius: 6px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            border: 1px solid currentColor;
        }

        .tag-cyan {
            color: var(--primary);
            background: var(--primary-light);
        }

        .tag-green {
            color: var(--accent-green);
            background: rgba(16, 185, 129, 0.1);
        }

        .tag-amber {
            color: var(--accent-amber);
            background: rgba(245, 158, 11, 0.1);
        }

        .tag-rose {
            color: var(--accent-rose);
            background: rgba(244, 63, 94, 0.1);
        }

        /* High-Density Data Tables */
        .data-table-container {
            background: var(--bg-surface);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 1.25rem;
            box-shadow: var(--shadow-sm);
            overflow-x: auto;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.86rem;
            text-align: left;
        }

        .data-table th {
            padding: 0.75rem 1rem;
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted);
            border-bottom: 1px solid var(--border-color);
            background: var(--bg-surface-elevated);
        }

        .data-table td {
            padding: 0.85rem 1rem;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-main);
            vertical-align: middle;
        }

        .data-table tbody tr {
            transition: background-color var(--transition-smooth);
        }

        .data-table tbody tr:hover {
            background: var(--bg-surface-elevated);
        }

        /* Modernized Button Suite */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.45rem;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-family: inherit;
            font-size: 0.84rem;
            font-weight: 600;
            letter-spacing: -0.01em;
            cursor: pointer;
            user-select: none;
            text-decoration: none;
            transition: all var(--transition-smooth);
            outline: none;
            border: 1px solid transparent;
            white-space: nowrap;
        }

        /* Primary Obsidian / Cyan Button */
        .btn-primary {
            background: linear-gradient(180deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: #ffffff !important;
            border-color: var(--primary-dark);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), var(--shadow-xs);
        }

        .btn-primary:hover {
            background: linear-gradient(180deg, var(--primary-hover) 0%, var(--primary-dark) 100%);
            transform: translateY(-1px);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.3), var(--shadow-sm);
            color: #ffffff !important;
        }

        .btn-secondary {
            background: var(--bg-surface-elevated);
            color: var(--text-main);
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-xs);
        }

        .btn-secondary:hover {
            background: var(--bg-surface);
            border-color: var(--border-hover);
            transform: translateY(-1px);
            box-shadow: var(--shadow-sm);
        }

        .btn-danger {
            background: var(--accent-rose);
            color: #ffffff !important;
            box-shadow: var(--shadow-xs);
        }

        .btn-danger:hover {
            opacity: 0.92;
            transform: translateY(-1px);
            box-shadow: var(--shadow-sm);
        }

        /* Custom Floating Dropdown Panels */
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-menu {
            display: block;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-6px) scale(0.96);
            position: absolute;
            right: 0;
            top: 100%;
            margin-top: 0.4rem;
            background: var(--bg-surface);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            box-shadow: var(--shadow-lg);
            min-width: 210px;
            z-index: 500;
            overflow: hidden;
            transition: opacity 0.18s cubic-bezier(0.16, 1, 0.3, 1), transform 0.18s cubic-bezier(0.16, 1, 0.3, 1), visibility 0.18s;
            pointer-events: none;
        }

        .dropdown-menu.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0) scale(1);
            pointer-events: auto;
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.65rem 1rem;
            color: var(--text-main);
            text-decoration: none;
            font-size: 0.84rem;
            font-weight: 600;
            transition: all var(--transition-smooth);
            width: 100%;
            border: none;
            background: transparent;
            text-align: left;
            cursor: pointer;
            border-left: 3px solid transparent;
        }

        .dropdown-item:hover {
            background: var(--primary-light);
            color: var(--primary);
            border-left-color: var(--primary);
        }

        /* Flash Alerts */
        .alert {
            padding: 0.85rem 1.25rem;
            border-radius: 8px;
            font-size: 0.88rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            border: 1px solid transparent;
            box-shadow: var(--shadow-xs);
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.12);
            color: var(--accent-green);
            border-color: var(--accent-green);
        }

        .alert-danger {
            background: rgba(244, 63, 94, 0.12);
            color: var(--accent-rose);
            border-color: var(--accent-rose);
        }

        /* Modal & Slide-Over Drawer Container */
        .modal-backdrop {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            align-items: center;
            justify-content: center;
            z-index: 999;
            backdrop-filter: blur(4px);
        }

        .modal-card {
            background: var(--bg-surface);
            border-radius: 12px;
            max-width: 520px;
            width: 90%;
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-lg);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            max-height: 90vh;
        }

        .modal-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
            background: var(--bg-surface-elevated);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-body {
            padding: 1.5rem;
            overflow-y: auto;
            flex: 1;
        }

        .modal-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid var(--border-color);
            background: var(--bg-surface-elevated);
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
        }
    </style>
</head>
<body>

    <!-- Fixed Top Navbar -->
    <header class="topbar">
        <!-- Far Left: System Name with Logo Icon -->
        <a href="{{ route('dashboard') }}" class="topbar-brand">
            <img src="{{ asset('icon.png') }}" alt="PragmaTick Logo" style="height: 28px; width: auto; object-fit: contain; vertical-align: middle;">
            <span><span style="color: var(--primary);">Pragma</span>Tick</span>
        </a>

        <!-- Far Right: Theme Switcher & Logout Dropdown -->
        <div class="topbar-actions">
            @php
                $currentUser = auth()->user();
            @endphp

            <!-- Light/Dark Mode Toggle -->
            <button class="btn btn-secondary" onclick="toggleTheme()" style="font-size: 0.8rem; padding: 0.35rem 0.75rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="12" cy="12" r="5"/><path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/></svg>
                <span id="themeText">Theme</span>
            </button>

            <!-- User Context / Logout Dropdown -->
            <div class="dropdown">
                <button class="btn btn-secondary" onclick="toggleDropdown('userMenu')" style="font-size: 0.8rem; padding: 0.35rem 0.75rem; gap: 0.5rem;">
                    <span class="user-avatar-badge">{{ strtoupper(substr($currentUser ? $currentUser->name : 'U', 0, 1)) }}</span>
                    <span>{{ $currentUser ? $currentUser->name : 'User' }}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="m6 9 6 6 6-6"/></svg>
                </button>
                <div class="dropdown-menu" id="userMenu">
                    <a href="{{ route('settings.edit') }}" class="dropdown-item">Account Settings</a>
                    <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                        @csrf
                        <button type="submit" class="dropdown-item" style="color: var(--accent-rose);">Log Out</button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Fixed Sidebar Navigation -->
    <aside class="sidebar">
        <nav class="sidebar-nav">
            <div class="nav-group-title">Workspaces</div>
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
                <span>Overview Dashboard</span>
            </a>
            <a href="{{ route('organizations.index') }}" class="nav-link {{ request()->routeIs('organizations.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/><path d="M6 12H4a2 2 0 0 0-2 2v8h20v-8a2 2 0 0 0-2-2h-2"/><path d="M10 6h4"/><path d="M10 10h4"/><path d="M10 14h4"/><path d="M10 18h4"/></svg>
                <span>Organizations</span>
            </a>
            <a href="{{ route('projects.index') }}" class="nav-link {{ request()->routeIs('projects.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M20 20a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.9a2 2 0 0 1-1.69-.9L9.6 3.9A2 2 0 0 0 7.93 3H4a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2Z"/></svg>
                <span>Projects & Tasks</span>
            </a>
            <a href="{{ route('wikis.index') }}" class="nav-link {{ request()->routeIs('wikis.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/><path d="M6 6h10"/><path d="M6 10h10"/></svg>
                <span>Wiki Documentation</span>
            </a>

            <div class="nav-group-title">Personal Hub</div>
            <a href="{{ route('calendar.index') }}" class="nav-link {{ request()->routeIs('calendar.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                <span>Calendar & Meetings</span>
            </a>
            <a href="{{ route('checklist.index') }}" class="nav-link {{ request()->routeIs('checklist.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="m9 11 3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                <span>Personal Checklist</span>
            </a>

            @if($currentUser && $currentUser->isSuperAdmin())
                <div class="nav-group-title">Administration</div>
                <a href="{{ route('contacts.index') }}" class="nav-link {{ request()->routeIs('contacts.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    <span>External Contacts</span>
                </a>
                <a href="{{ route('recovery.index') }}" class="nav-link {{ request()->routeIs('recovery.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/><path d="m9 12 2 2 4-4"/></svg>
                    <span>System Recovery & Logs</span>
                </a>
            @endif

            <div class="nav-group-title">Account</div>
            <a href="{{ route('settings.edit') }}" class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.38a2 2 0 0 0-.73-2.73l-.15-.09a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
                <span>Settings & User Console</span>
            </a>
        </nav>

        <div class="sidebar-footer">
            <div style="font-size: 0.75rem; font-weight: 600; color: var(--text-muted);">
                Role: {{ $currentUser ? ($currentUser->isSuperAdmin() ? 'Global Super Admin' : ($currentUser->organizations()->wherePivot('role', 'org_admin')->exists() ? 'Organization Admin' : 'Workspace Member')) : 'Guest' }}
            </div>
        </div>
    </aside>

    <!-- Main Workspace -->
    <main class="main-workspace">
        <!-- Flash Messages -->
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <strong>Validation Error:</strong>
                <ul style="margin-top: 0.5rem; padding-left: 1.5rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Confirmation Modal for Soft Deletes -->
    <div id="deleteModal" class="modal-backdrop">
        <div class="modal-card" style="max-width: 440px;">
            <div class="modal-header">
                <span style="font-size: 0.75rem; font-weight: 800; color: var(--accent-rose); text-transform: uppercase; letter-spacing: 0.05em;">
                    [CONFIRMATION REQUIRED]
                </span>
                <button type="button" onclick="closeDeleteModal()" style="background:none; border:none; color: var(--text-muted); cursor:pointer; font-size: 1rem;">✕</button>
            </div>
            <div class="modal-body">
                <h3 style="font-size: 1.2rem; font-weight: 800; margin-bottom: 0.5rem;" id="deleteModalTitle">Confirm Action</h3>
                <p style="font-size: 0.88rem; color: var(--text-muted);" id="deleteModalMessage">
                    Are you sure you want to perform soft-deletion on this resource?
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Confirm Soft Delete</button>
            </div>
        </div>
    </div>

    <script>
        function switchPersona(userId) {
            const form = document.getElementById('switchUserForm');
            form.action = '/switch-user/' + userId;
            form.submit();
        }

        function toggleTheme() {
            const currentTheme = document.documentElement.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('pragmatick_theme', newTheme);
        }

        const savedTheme = localStorage.getItem('pragmatick_theme');
        if (savedTheme) {
            document.documentElement.setAttribute('data-theme', savedTheme);
        }

        function toggleDropdown(id) {
            const el = document.getElementById(id);
            if (el) {
                el.classList.toggle('show');
            }
        }

        window.addEventListener('click', function(e) {
            if (!e.target.closest('.dropdown')) {
                document.querySelectorAll('.dropdown-menu').forEach(m => m.classList.remove('show'));
            }
        });

        let activeDeleteForm = null;

        function promptDelete(resourceName, formElement) {
            activeDeleteForm = formElement;
            document.getElementById('deleteModalTitle').textContent = `Soft Delete ${resourceName}?`;
            document.getElementById('deleteModalMessage').textContent = `This action will place "${resourceName}" into the soft-deleted state. Super Admins can restore it from System Recovery.`;
            document.getElementById('deleteModal').style.display = 'flex';
            return false;
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').style.display = 'none';
            activeDeleteForm = null;
        }

        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            if (activeDeleteForm) {
                activeDeleteForm.submit();
            }
        });

        // Global Backdrop Click Handler: Automatically close modal popups when clicking on backdrop overlay
        document.addEventListener('click', function(e) {
            if (e.target && e.target.nodeType === 1) {
                const targetId = e.target.id || '';
                const isFixedBackdrop = e.target.classList.contains('modal-backdrop') || 
                                        (e.target.style && e.target.style.position === 'fixed' && (targetId.toLowerCase().includes('modal') || e.target.style.zIndex >= 500));
                
                if (isFixedBackdrop && e.target.style.display !== 'none' && e.target.style.display !== '') {
                    // Close modal if user clicked directly on backdrop overlay outside inner modal box
                    e.target.style.display = 'none';
                }
            }
        });
    </script>
</body>
</html>
