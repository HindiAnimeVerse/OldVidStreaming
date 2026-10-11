<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - VidStreaming</title>
    @php $favicon = \App\Models\Setting::get('site_favicon'); @endphp
    @if($favicon)
    <link rel="icon" href="{{ asset($favicon) }}">
    @endif
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        *,
        *::before,
        *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --black: #000;
            --dark: #0a0a0a;
            --dark-2: #111;
            --dark-3: #1a1a1a;
            --dark-4: #222;
            --white: #fff;
            --gray: #888;
            --light-gray: #ccc;
            --success: #22c55e;
            --warning: #f59e0b;
            --error: #ef4444;
            --info: #3b82f6;
            --sidebar-width: 280px;
            --header-height: 68px;
        }

        html,
        body {
            height: 100%;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--black);
            color: var(--white);
            overflow-x: hidden;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        /* ===== LAYOUT ===== */
        .app {
            display: flex;
            min-height: 100vh;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: var(--dark);
            border-right: 1px solid rgba(255, 255, 255, 0.08);
            display: flex;
            flex-direction: column;
            z-index: 50;
            transition: transform 0.3s ease;
        }

        .sidebar-header {
            padding: 1.5rem 1.75rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .sidebar-logo-icon {
            width: 40px;
            height: 40px;
            background: var(--white);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sidebar-logo-icon svg {
            width: 20px;
            height: 20px;
            fill: var(--black);
        }

        .sidebar-logo-text {
            font-size: 1.25rem;
            font-weight: 800;
        }

        .sidebar-nav {
            flex: 1;
            padding: 1.25rem 0;
            overflow-y: auto;
        }

        .nav-section {
            margin-bottom: 1.75rem;
        }

        .nav-section-title {
            padding: 0.5rem 1.75rem 0.875rem;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--gray);
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 0.875rem;
            padding: 0.875rem 1.75rem;
            color: var(--gray);
            font-size: 0.925rem;
            font-weight: 500;
            transition: all 0.2s ease;
            cursor: pointer;
            border-left: 3px solid transparent;
        }

        .nav-item:hover {
            background: rgba(255, 255, 255, 0.05);
            color: var(--white);
        }

        .nav-item.active {
            background: rgba(255, 255, 255, 0.08);
            color: var(--white);
            border-left-color: var(--white);
        }

        .nav-item svg {
            width: 22px;
            height: 22px;
            stroke: currentColor;
            stroke-width: 1.5;
            fill: none;
            flex-shrink: 0;
        }

        .nav-badge {
            margin-left: auto;
            padding: 0.2rem 0.6rem;
            background: var(--info);
            border-radius: 10px;
            font-size: 0.7rem;
            font-weight: 600;
            color: var(--white);
        }

        .sidebar-footer {
            padding: 1.25rem 1.75rem;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
        }

        .user-card {
            display: flex;
            align-items: center;
            gap: 0.875rem;
            padding: 0.875rem;
            background: var(--dark-2);
            border-radius: 14px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .user-card:hover {
            background: var(--dark-3);
        }

        .user-avatar {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, var(--gray), var(--dark-4));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            font-weight: 700;
        }

        .user-info {
            flex: 1;
            min-width: 0;
        }

        .user-name {
            font-size: 0.925rem;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-email {
            font-size: 0.8rem;
            color: var(--gray);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* ===== MAIN CONTENT ===== */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }

        .header {
            height: var(--header-height);
            background: var(--dark);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2.5rem;
            position: sticky;
            top: 0;
            z-index: 40;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 1.25rem;
        }

        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            cursor: pointer;
            padding: 0.5rem;
        }

        .mobile-menu-btn span {
            display: block;
            width: 22px;
            height: 2px;
            background: var(--white);
            margin: 5px 0;
            transition: all 0.3s;
        }

        .page-title {
            font-size: 1.375rem;
            font-weight: 700;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 1.25rem;
        }

        /* ===== PAGE CONTENT ===== */
        .page-content {
            padding: 2.5rem;
        }

        /* ===== SIDEBAR OVERLAY ===== */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.7);
            z-index: 45;
        }

        .sidebar-overlay.open {
            display: block;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .mobile-menu-btn {
                display: block;
            }

            .header {
                padding: 0 1.5rem;
            }

            .page-content {
                padding: 1.5rem;
            }
        }

        @media (max-width: 640px) {
            .page-content {
                padding: 1rem;
            }
        }

        @yield('styles')
    </style>
</head>

<body>
    <div class="app">
        <!-- Sidebar Overlay -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <a href="{{ url('/') }}" class="sidebar-logo">
                    <div class="sidebar-logo-icon">
                        <svg viewBox="0 0 24 24">
                            <polygon points="5,3 19,12 5,21" />
                        </svg>
                    </div>
                    <span class="sidebar-logo-text">VidStreaming</span>
                </a>
            </div>

            <nav class="sidebar-nav">
                <div class="nav-section">
                    <div class="nav-section-title">Main</div>
                    <a href="{{ url('/dashboard') }}" class="nav-item {{ request()->is('dashboard') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24">
                            <rect x="3" y="3" width="7" height="7" />
                            <rect x="14" y="3" width="7" height="7" />
                            <rect x="14" y="14" width="7" height="7" />
                            <rect x="3" y="14" width="7" height="7" />
                        </svg>
                        Dashboard
                    </a>
                    <a href="{{ route('files') }}" class="nav-item {{ request()->routeIs('files') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24">
                            <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z" />
                            <polyline points="14,2 14,8 20,8" />
                        </svg>
                        My Files
                    </a>
                    <a href="#" class="nav-item">
                        <svg viewBox="0 0 24 24">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                            <polyline points="17,8 12,3 7,8" />
                            <line x1="12" y1="3" x2="12" y2="15" />
                        </svg>
                        Upload
                    </a>
                </div>

                <div class="nav-section">
                    <div class="nav-section-title">Account</div>
                    <a href="{{ url('/settings') }}" class="nav-item {{ request()->is('settings') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="3" />
                            <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z" />
                        </svg>
                        Settings
                    </a>
                    <a href="{{ route('player.settings') }}" class="nav-item {{ request()->routeIs('player.settings') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24">
                            <polygon points="5,3 19,12 5,21" />
                        </svg>
                        Player Settings
                    </a>
                    <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                        @csrf
                        <button type="submit" class="nav-item" style="width: 100%; border: none; background: none; cursor: pointer; text-align: left;">
                            <svg viewBox="0 0 24 24">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                                <polyline points="16,17 21,12 16,7" />
                                <line x1="21" y1="12" x2="9" y2="12" />
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </nav>

            <div class="sidebar-footer">
                <div class="user-card">
                    <div class="user-avatar">{{ strtoupper(substr($user->name ?? 'U', 0, 2)) }}</div>
                    <div class="user-info">
                        <div class="user-name">{{ $user->name ?? 'User' }}</div>
                        <div class="user-email">{{ $user->email ?? '' }}</div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <header class="header">
                <div class="header-left">
                    <button class="mobile-menu-btn" id="mobileMenuBtn">
                        <span></span><span></span><span></span>
                    </button>
                    <h1 class="page-title">@yield('page_title', 'Dashboard')</h1>
                </div>
                <div class="header-right">
                    @yield('header_actions')
                </div>
            </header>

            <!-- Page Content -->
            <div class="page-content">
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        // Mobile menu toggle
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        mobileMenuBtn.addEventListener('click', () => {
            sidebar.classList.toggle('open');
            sidebarOverlay.classList.toggle('open');
        });

        sidebarOverlay.addEventListener('click', () => {
            sidebar.classList.remove('open');
            sidebarOverlay.classList.remove('open');
        });
    </script>

    {{-- Auto-sync removed - use Sync Now button on files page --}}

    @yield('scripts')
</body>

</html>