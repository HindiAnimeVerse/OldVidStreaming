<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - VidStreaming Admin</title>
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
            --sidebar-width: 260px;
            --header-height: 60px;
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
        .admin-app {
            display: flex;
            min-height: 100vh;
        }

        /* ===== SIDEBAR ===== */
        .admin-sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: var(--dark);
            border-right: 1px solid rgba(255, 255, 255, 0.06);
            display: flex;
            flex-direction: column;
            z-index: 50;
            transition: transform 0.3s ease;
        }

        .sidebar-brand {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .brand-icon {
            width: 36px;
            height: 36px;
            background: var(--white);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .brand-icon svg {
            width: 18px;
            height: 18px;
            fill: var(--black);
        }

        .brand-text {
            font-size: 1rem;
            font-weight: 700;
        }

        .brand-text span {
            color: var(--gray);
            font-weight: 500;
            font-size: 0.85rem;
            margin-left: 0.25rem;
        }

        .sidebar-nav {
            flex: 1;
            padding: 1rem 0;
            overflow-y: auto;
        }

        .nav-section {
            margin-bottom: 1.5rem;
        }

        .nav-label {
            padding: 0.5rem 1.5rem 0.625rem;
            font-size: 0.65rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--gray);
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.625rem 1.5rem;
            color: var(--gray);
            font-size: 0.85rem;
            font-weight: 500;
            transition: all 0.2s;
            border-left: 2px solid transparent;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.03);
            color: var(--light-gray);
        }

        .nav-link.active {
            background: rgba(255, 255, 255, 0.08);
            color: var(--white);
            border-left-color: var(--white);
        }

        .nav-link svg {
            width: 18px;
            height: 18px;
            stroke: currentColor;
            stroke-width: 1.5;
            fill: none;
            flex-shrink: 0;
        }

        .nav-badge {
            margin-left: auto;
            padding: 0.125rem 0.5rem;
            background: var(--white);
            color: var(--black);
            border-radius: 8px;
            font-size: 0.65rem;
            font-weight: 600;
        }

        .sidebar-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.06);
        }

        .admin-user {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .admin-avatar {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--gray), var(--dark-4));
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            font-weight: 700;
        }

        .admin-info {
            flex: 1;
            min-width: 0;
        }

        .admin-name {
            font-size: 0.85rem;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .admin-role {
            font-size: 0.7rem;
            color: var(--gray);
        }

        /* ===== MAIN CONTENT ===== */
        .admin-main {
            flex: 1;
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }

        .admin-header {
            height: var(--header-height);
            background: var(--dark);
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            position: sticky;
            top: 0;
            z-index: 40;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .mobile-toggle {
            display: none;
            background: none;
            border: none;
            cursor: pointer;
            padding: 0.5rem;
        }

        .mobile-toggle span {
            display: block;
            width: 20px;
            height: 2px;
            background: var(--white);
            margin: 4px 0;
        }

        .page-title {
            font-size: 1.1rem;
            font-weight: 700;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .header-btn {
            padding: 0.5rem 1rem;
            background: var(--dark-3);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 8px;
            color: var(--light-gray);
            font-size: 0.8rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .header-btn:hover {
            background: var(--dark-4);
            border-color: rgba(255, 255, 255, 0.15);
        }

        .header-btn svg {
            width: 16px;
            height: 16px;
            stroke: currentColor;
            stroke-width: 1.5;
            fill: none;
        }

        .admin-content {
            padding: 1.5rem 2rem;
        }

        /* ===== OVERLAY ===== */
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
            .admin-sidebar {
                transform: translateX(-100%);
            }

            .admin-sidebar.open {
                transform: translateX(0);
            }

            .admin-main {
                margin-left: 0;
            }

            .mobile-toggle {
                display: block;
            }

            .admin-header {
                padding: 0 1rem;
            }

            .admin-content {
                padding: 1rem;
            }
        }

        /* Toast Notifications */
        .toast-container {
            position: fixed;
            top: 1.5rem;
            right: 1.5rem;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            pointer-events: none;
        }

        .toast {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.875rem 1.25rem;
            background: var(--dark-2);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4);
            pointer-events: auto;
            animation: toastIn 0.3s ease;
            max-width: 350px;
        }

        .toast.toast-success {
            border-left: 3px solid #22c55e;
        }

        .toast.toast-error {
            border-left: 3px solid #ef4444;
        }

        .toast.toast-warning {
            border-left: 3px solid #f59e0b;
        }

        .toast.toast-info {
            border-left: 3px solid #3b82f6;
        }

        .toast-icon {
            width: 20px;
            height: 20px;
            flex-shrink: 0;
        }

        .toast-success .toast-icon {
            color: #22c55e;
        }

        .toast-error .toast-icon {
            color: #ef4444;
        }

        .toast-warning .toast-icon {
            color: #f59e0b;
        }

        .toast-info .toast-icon {
            color: #3b82f6;
        }

        .toast-message {
            flex: 1;
            font-size: 0.85rem;
            color: var(--white);
        }

        .toast-close {
            background: none;
            border: none;
            color: var(--gray);
            cursor: pointer;
            padding: 0.25rem;
            opacity: 0.6;
            transition: opacity 0.2s;
        }

        .toast-close:hover {
            opacity: 1;
        }

        @keyframes toastIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .toast.toast-out {
            animation: toastOut 0.3s ease forwards;
        }

        @keyframes toastOut {
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }

        @yield('styles')
    </style>
</head>

<body>
    <div class="admin-app">
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- Admin Sidebar -->
        <aside class="admin-sidebar" id="adminSidebar">
            <div class="sidebar-brand">
                <div class="brand-icon">
                    <svg viewBox="0 0 24 24">
                        <polygon points="5,3 19,12 5,21" />
                    </svg>
                </div>
                <div class="brand-text">VidStreaming <span>Admin</span></div>
            </div>

            <nav class="sidebar-nav">
                <div class="nav-section">
                    <div class="nav-label">Overview</div>
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24">
                            <rect x="3" y="3" width="7" height="7" />
                            <rect x="14" y="3" width="7" height="7" />
                            <rect x="14" y="14" width="7" height="7" />
                            <rect x="3" y="14" width="7" height="7" />
                        </svg>
                        Dashboard
                    </a>
                </div>

                <div class="nav-section">
                    <div class="nav-label">Management</div>
                    <a href="{{ route('admin.users') }}" class="nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                            <circle cx="9" cy="7" r="4" />
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                        </svg>
                        Users
                    </a>
                    <a href="{{ route('admin.files') }}" class="nav-link {{ request()->routeIs('admin.files') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24">
                            <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z" />
                            <polyline points="14,2 14,8 20,8" />
                        </svg>
                        Files
                    </a>
                    <a href="#" class="nav-link">
                        <svg viewBox="0 0 24 24">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                        </svg>
                        DMCA
                    </a>
                </div>

                <div class="nav-section">
                    <div class="nav-label">System</div>
                    <a href="{{ route('admin.settings') }}" class="nav-link {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="3" />
                            <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z" />
                        </svg>
                        Settings
                    </a>
                    <a href="{{ route('admin.ads') }}" class="nav-link {{ request()->routeIs('admin.ads') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24">
                            <rect x="2" y="7" width="20" height="14" rx="2" ry="2" />
                            <path d="M16 3h-8l-2 4h12l-2-4z" />
                        </svg>
                        Monetization
                    </a>
                </div>
            </nav>

            <div class="sidebar-footer">
                <div class="admin-user">
                    <div class="admin-avatar">{{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 2)) }}</div>
                    <div class="admin-info">
                        <div class="admin-name">{{ Auth::user()->name ?? 'Admin' }}</div>
                        <div class="admin-role">Administrator</div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            <header class="admin-header">
                <div class="header-left">
                    <button class="mobile-toggle" id="mobileToggle">
                        <span></span><span></span><span></span>
                    </button>
                    <h1 class="page-title">@yield('page_title', 'Dashboard')</h1>
                </div>
                <div class="header-actions">
                    <a href="{{ url('/dashboard') }}" class="header-btn">
                        <svg viewBox="0 0 24 24">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                            <polyline points="9,22 9,12 15,12 15,22" />
                        </svg>
                        User Panel
                    </a>
                    <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                        @csrf
                        <button type="submit" class="header-btn">
                            <svg viewBox="0 0 24 24">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                                <polyline points="16,17 21,12 16,7" />
                                <line x1="21" y1="12" x2="9" y2="12" />
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </header>

            <div class="admin-content">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>

    <script>
        const mobileToggle = document.getElementById('mobileToggle');
        const adminSidebar = document.getElementById('adminSidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        mobileToggle.addEventListener('click', () => {
            adminSidebar.classList.toggle('open');
            sidebarOverlay.classList.toggle('open');
        });

        sidebarOverlay.addEventListener('click', () => {
            adminSidebar.classList.remove('open');
            sidebarOverlay.classList.remove('open');
        });

        // Global toast notification function
        function showToast(message, type = 'info', duration = 4000) {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;

            const icons = {
                success: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20,6 9,17 4,12"/></svg>',
                error: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>',
                warning: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>',
                info: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>'
            };

            toast.innerHTML = `
                <span class="toast-icon">${icons[type] || icons.info}</span>
                <span class="toast-message">${message}</span>
                <button class="toast-close" onclick="this.parentElement.remove()">✕</button>
            `;

            container.appendChild(toast);

            setTimeout(() => {
                toast.classList.add('toast-out');
                setTimeout(() => toast.remove(), 300);
            }, duration);
        }
    </script>

    {{-- Auto-sync Google Photos in background (if user has auth configured) --}}
    @if(auth()->user()->gpmc_auth_data)
    <script>
        (async function autoSync() {
            try {
                const res = await fetch('{{ route("settings.cloud.sync") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });
                const data = await res.json();
                // Show toast only if there were actual changes - no page reload
                if (data.success && (data.inserted > 0 || data.deleted > 0)) {
                    if (typeof showToast === 'function') {
                        showToast(`Synced: +${data.inserted} new, -${data.deleted} removed`, 'success');
                    }
                }
            } catch (e) {
                console.log('Auto-sync error:', e);
            }
        })();
    </script>
    @endif

    @yield('scripts')
</body>

</html>