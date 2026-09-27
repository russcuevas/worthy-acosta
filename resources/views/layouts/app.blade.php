@php
    $isAssistant = (auth()->check() && auth()->user()->role === 'assistant') || (isset($role) && $role === 'assistant');
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Worthy Acosta</title>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @yield('styles')
</head>

<body>
    <div class="app-wrapper">
        <!-- Sidebar -->
        <aside class="app-sidebar" id="appSidebar">
            <div class="sidebar-header">
                <a href="{{ route($isAssistant ? 'assistant.electoral' : 'admin.electoral') }}"
                    class="brand-link">
                    <img src="{{ asset('images/WA-Logo.png') }}" alt="Worthy Acosta Logo" class="brand-logo-img">
                </a>
                <button class="sidebar-close-btn" id="sidebarCloseBtn" aria-label="Close Sidebar">&times;</button>
            </div>

            <div class="sidebar-content">
                <div class="nav-section-title">Navigation</div>
                <ul class="nav-list">
                    <li class="nav-item">
                        <a href="{{ route($isAssistant ? 'assistant.electoral' : 'admin.electoral') }}"
                            class="nav-link {{ request()->routeIs('*electoral*') || request()->routeIs('*.dashboard') ? 'active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
                            </svg>
                            <span>Electoral Data</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route($isAssistant ? 'assistant.assistance.index' : 'admin.assistance.index') }}"
                            class="nav-link {{ request()->routeIs('*assistance*') ? 'active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                            </svg>
                            <span>Assistance</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route($isAssistant ? 'assistant.events.index' : 'admin.events.index') }}"
                            class="nav-link {{ request()->routeIs('*events*') ? 'active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                            </svg>
                            <span>Events</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route($isAssistant ? 'assistant.directory.index' : 'admin.directory.index') }}"
                            class="nav-link {{ request()->routeIs('*directory*') ? 'active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                            <span>Directory</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route($isAssistant ? 'assistant.issues.index' : 'admin.issues.index') }}"
                            class="nav-link {{ request()->routeIs('*issues*') ? 'active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                            </svg>
                            <span>Issues</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route($isAssistant ? 'assistant.demography.index' : 'admin.demography.index') }}"
                            class="nav-link {{ request()->routeIs('*demography*') ? 'active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.999-3.199a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                            </svg>
                            <span>Demography</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route($isAssistant ? 'assistant.survey.index' : 'admin.survey.index') }}"
                            class="nav-link {{ request()->routeIs('*survey*') ? 'active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                            </svg>
                            <span>Survey</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="sidebar-footer">
                <div class="user-profile-badge">
                    <div class="avatar">
                        @if(auth()->check())
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        @else
                            @yield('user_initials', 'WA')
                        @endif
                    </div>
                    <div class="user-info">
                        <div class="user-name">
                            @if(auth()->check())
                                {{ auth()->user()->name }}
                            @else
                                @yield('user_name', 'Russel Acosta')
                            @endif
                        </div>
                        <div class="user-role">
                            @if(auth()->check())
                                {{ auth()->user()->role === 'assistant' ? 'Assistant Portal' : 'Administrator' }}
                            @else
                                @yield('user_role_label', 'Administrator')
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="app-main">
            <!-- Topbar Header -->
            <header class="app-header">
                <div class="header-left">
                    <button type="button" class="sidebar-toggle-btn" id="sidebarToggleBtn" title="Hide / Show Sidebar" aria-label="Toggle Sidebar">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>
                </div>

                <div class="header-right">
                    @hasSection('role_badge')
                        @yield('role_badge')
                    @elseif(auth()->check())
                        @if(auth()->user()->role === 'assistant')
                            <span class="role-badge-pill role-badge-assistant">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                </svg>
                                Assistant Mode
                            </span>
                        @else
                            <span class="role-badge-pill role-badge-admin">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                                </svg>
                                Administrator
                            </span>
                        @endif
                    @endif

                    <!-- Logout Link -->
                    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="header-btn-icon" title="Logout" style="color: #DC2626;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                            </svg>
                        </button>
                    </form>
                </div>
            </header>

            <!-- Dashboard Content Container -->
            <main class="dashboard-container">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Floating Toast Notification (Bottom Right) -->
    <div class="bottom-toast-container" id="bottomToastContainer">
        @if (session('error'))
            <div class="bottom-toast toast-error" id="appToast">
                <span class="toast-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="#EF4444">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                    </svg>
                </span>
                <span class="toast-text">{{ session('error') }}</span>
                <button type="button" class="toast-close-btn" onclick="dismissToast(this)" aria-label="Close">&times;</button>
            </div>
        @elseif (session('success'))
            <div class="bottom-toast toast-success" id="appToast">
                <span class="toast-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="#10B981">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                    </svg>
                </span>
                <span class="toast-text">{{ session('success') }}</span>
                <button type="button" class="toast-close-btn" onclick="dismissToast(this)" aria-label="Close">&times;</button>
            </div>
        @elseif (session('info'))
            <div class="bottom-toast toast-info" id="appToast">
                <span class="toast-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="var(--color-wave-cyan)">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                    </svg>
                </span>
                <span class="toast-text">{{ session('info') }}</span>
                <button type="button" class="toast-close-btn" onclick="dismissToast(this)" aria-label="Close">&times;</button>
            </div>
        @endif
    </div>

    <!-- Custom JS -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        function dismissToast(el) {
            const toast = el.closest ? el.closest('.bottom-toast') : el;
            if (!toast) return;
            toast.classList.add('hide');
            setTimeout(() => { toast.remove(); }, 350);
        }
        document.addEventListener('DOMContentLoaded', () => {
            const toast = document.getElementById('appToast');
            if (toast) {
                setTimeout(() => { dismissToast(toast); }, 3200);
            }
        });
    </script>
    @yield('scripts')
</body>

</html>
