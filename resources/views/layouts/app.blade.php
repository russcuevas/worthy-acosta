@php
    $isAssistant = (auth()->check() && auth()->user()->role === 'assistant') || (isset($role) && $role === 'assistant') || request()->is('assistant*');
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
                    @if(!$isAssistant)
                    <li class="nav-item">
                        <a href="{{ route('admin.users.index') }}"
                            class="nav-link {{ request()->routeIs('*users*') ? 'active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                            </svg>
                            <span>User Accounts</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </div>

            <div class="sidebar-footer">
                <div class="user-profile-badge" id="sidebarUserProfileBtn" title="Click to edit profile & change password" style="cursor: pointer;">
                    <div class="avatar" id="sidebarUserAvatar">
                        @if(auth()->check())
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        @else
                            @yield('user_initials', 'WA')
                        @endif
                    </div>
                    <div class="user-info">
                        <div class="user-name" id="sidebarUserName">
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
                    <div class="user-badge-edit-icon" title="Edit Profile & Password">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z" />
                        </svg>
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
                    @if($isAssistant)
                        <span class="role-badge-pill role-badge-assistant">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                            Assistant
                        </span>
                    @else
                        <span class="role-badge-pill role-badge-admin">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                            </svg>
                            Admin
                        </span>
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

    <!-- Global User Profile & Change Password Modal -->
    <div class="global-profile-backdrop" id="globalProfileModalBackdrop">
        <div class="global-profile-dialog">
            <div class="global-profile-header">
                <div class="global-profile-user-summary">
                    <div class="global-profile-avatar" id="modalUserAvatar">
                        {{ auth()->check() ? strtoupper(substr(auth()->user()->name, 0, 2)) : 'WA' }}
                    </div>
                    <div>
                        <h3 id="modalUserName">{{ auth()->check() ? auth()->user()->name : 'User' }}</h3>
                        <p id="modalUserEmail">{{ auth()->check() ? auth()->user()->email : 'user@worthyacosta.ph' }}</p>
                    </div>
                </div>
                <button type="button" class="global-profile-close" id="btnCloseGlobalProfile" aria-label="Close">&times;</button>
            </div>

            <!-- Modal Tabs -->
            <div class="global-profile-tabs">
                <button type="button" class="global-profile-tab active" data-tab="tabProfileInfo" id="tabBtnProfileInfo">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                    <span>Edit Profile</span>
                </button>
                <button type="button" class="global-profile-tab" data-tab="tabChangePassword" id="tabBtnChangePassword">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                    </svg>
                    <span>Change Password</span>
                </button>
            </div>

            <!-- Tab 1: Profile Info -->
            <div class="global-profile-tab-content active" id="tabProfileInfo">
                <form id="globalProfileForm">
                    @csrf
                    <div class="global-profile-body">
                        <div class="global-profile-field">
                            <label for="modalInputName">Full Name *</label>
                            <input type="text" id="modalInputName" name="name" class="global-profile-input" value="{{ auth()->check() ? auth()->user()->name : '' }}" required>
                        </div>
                        <div class="global-profile-field">
                            <label for="modalInputUsername">Username *</label>
                            <input type="text" id="modalInputUsername" name="username" class="global-profile-input" value="{{ auth()->check() ? auth()->user()->username : '' }}" required>
                        </div>
                        <div class="global-profile-field">
                            <label for="modalInputEmail">Email Address *</label>
                            <input type="email" id="modalInputEmail" name="email" class="global-profile-input" value="{{ auth()->check() ? auth()->user()->email : '' }}" required>
                        </div>
                        <div class="global-profile-field">
                            <label>System Role</label>
                            <input type="text" class="global-profile-input" value="{{ auth()->check() && auth()->user()->role === 'admin' ? 'Administrator (Full Access)' : 'Assistant Officer (Forms & Records)' }}" readonly style="background:#F1F5F9; color:#64748B; cursor:not-allowed;">
                        </div>
                    </div>
                    <div class="global-profile-footer">
                        <button type="button" class="btn-profile-modal-cancel" id="btnCancelProfileModal">Cancel</button>
                        <button type="submit" class="btn-profile-modal-save" id="btnSubmitModalProfile">Save Profile Changes</button>
                    </div>
                </form>
            </div>

            <!-- Tab 2: Change Password -->
            <div class="global-profile-tab-content" id="tabChangePassword" style="display:none;">
                <form id="globalPasswordForm">
                    @csrf
                    <div class="global-profile-body">
                        <div class="global-profile-field">
                            <label for="modalCurrentPassword">Current Password *</label>
                            <div class="global-input-wrapper">
                                <input type="password" id="modalCurrentPassword" name="current_password" class="global-profile-input" required autocomplete="current-password">
                                <button type="button" class="global-eye-btn" onclick="toggleModalEye('modalCurrentPassword')" title="Toggle visibility">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="global-profile-field">
                            <label for="modalNewPassword">New Password * <span style="font-size:0.74rem; color:var(--text-muted); font-weight:normal;">(Minimum 6 characters)</span></label>
                            <div class="global-input-wrapper">
                                <input type="password" id="modalNewPassword" name="password" class="global-profile-input" minlength="6" required autocomplete="new-password">
                                <button type="button" class="global-eye-btn" onclick="toggleModalEye('modalNewPassword')" title="Toggle visibility">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="global-profile-field">
                            <label for="modalConfirmPassword">Confirm New Password *</label>
                            <div class="global-input-wrapper">
                                <input type="password" id="modalConfirmPassword" name="password_confirmation" class="global-profile-input" minlength="6" required autocomplete="new-password">
                                <button type="button" class="global-eye-btn" onclick="toggleModalEye('modalConfirmPassword')" title="Toggle visibility">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="global-profile-footer">
                        <button type="button" class="btn-profile-modal-cancel" id="btnCancelPasswordModal">Cancel</button>
                        <button type="submit" class="btn-profile-modal-save" id="btnSubmitModalPassword">Update Password</button>
                    </div>
                </form>
            </div>
        </div>
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

        // Global Toast Notification Helper
        window.showAppToast = function(message, type = 'success') {
            const container = document.getElementById('bottomToastContainer');
            if (!container) return;

            const toast = document.createElement('div');
            toast.className = `bottom-toast toast-${type === 'error' ? 'error' : 'success'}`;

            const iconSvg = type === 'error'
                ? `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="#EF4444"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" /></svg>`
                : `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="#10B981"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>`;

            toast.innerHTML = `
                <span class="toast-icon">${iconSvg}</span>
                <span class="toast-text">${message}</span>
                <button type="button" class="toast-close-btn" onclick="dismissToast(this)" aria-label="Close">&times;</button>
            `;

            container.appendChild(toast);
            setTimeout(() => { dismissToast(toast); }, 3500);
        };

        window.toggleModalEye = function(inputId) {
            const inp = document.getElementById(inputId);
            if (!inp) return;
            inp.type = inp.type === 'password' ? 'text' : 'password';
        };

        document.addEventListener('DOMContentLoaded', () => {
            const toast = document.getElementById('appToast');
            if (toast) {
                setTimeout(() => { dismissToast(toast); }, 3200);
            }

            // Global Profile Modal Controls
            const profileModal = document.getElementById('globalProfileModalBackdrop');
            const openBtn = document.getElementById('btnOpenGlobalProfile');
            const sidebarBtn = document.getElementById('sidebarUserProfileBtn');
            const closeBtn = document.getElementById('btnCloseGlobalProfile');
            const cancelBtn1 = document.getElementById('btnCancelProfileModal');
            const cancelBtn2 = document.getElementById('btnCancelPasswordModal');

            function openProfileModal() {
                if (profileModal) {
                    profileModal.style.display = 'flex';
                }
            }

            function closeProfileModal() {
                if (profileModal) {
                    profileModal.style.display = 'none';
                }
            }

            if (openBtn) openBtn.addEventListener('click', openProfileModal);
            if (sidebarBtn) sidebarBtn.addEventListener('click', openProfileModal);
            if (closeBtn) closeBtn.addEventListener('click', closeProfileModal);
            if (cancelBtn1) cancelBtn1.addEventListener('click', closeProfileModal);
            if (cancelBtn2) cancelBtn2.addEventListener('click', closeProfileModal);

            if (profileModal) {
                profileModal.addEventListener('click', function(e) {
                    if (e.target === profileModal) {
                        closeProfileModal();
                    }
                });
            }

            // Tabs Switcher
            const tabBtns = document.querySelectorAll('.global-profile-tab');
            tabBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    tabBtns.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');

                    const targetTabId = this.getAttribute('data-tab');
                    document.querySelectorAll('.global-profile-tab-content').forEach(content => {
                        content.style.display = content.id === targetTabId ? 'block' : 'none';
                    });
                });
            });

            // Handle Profile Form Submit
            const profileForm = document.getElementById('globalProfileForm');
            if (profileForm) {
                profileForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const submitBtn = document.getElementById('btnSubmitModalProfile');
                    submitBtn.disabled = true;
                    submitBtn.textContent = 'Saving...';

                    fetch("{{ route('profile.update') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            name: document.getElementById('modalInputName').value,
                            username: document.getElementById('modalInputUsername').value,
                            email: document.getElementById('modalInputEmail').value
                        })
                    })
                    .then(res => res.json().then(data => ({ status: res.status, body: data })))
                    .then(({ status, body }) => {
                        submitBtn.disabled = false;
                        submitBtn.textContent = 'Save Profile Changes';

                        if (status === 200 && body.success) {
                            window.showAppToast(body.message || 'Profile updated successfully!');
                            closeProfileModal();

                            // Live update header & sidebar
                            if (body.user) {
                                document.getElementById('modalUserName').textContent = body.user.name;
                                document.getElementById('modalUserEmail').textContent = body.user.email;
                                document.getElementById('modalUserAvatar').textContent = body.user.initials;

                                const sideName = document.getElementById('sidebarUserName');
                                if (sideName) sideName.textContent = body.user.name;
                                const sideAvatar = document.getElementById('sidebarUserAvatar');
                                if (sideAvatar) sideAvatar.textContent = body.user.initials;

                                const pageHeroName = document.getElementById('heroUserName');
                                if (pageHeroName) pageHeroName.textContent = body.user.name;
                                const pageHeroEmail = document.getElementById('heroUserEmail');
                                if (pageHeroEmail) pageHeroEmail.textContent = body.user.email;
                                const pageHeroAvatar = document.getElementById('heroUserAvatar');
                                if (pageHeroAvatar) pageHeroAvatar.textContent = body.user.initials;
                            }
                        } else {
                            const errMsg = body.message || (body.errors ? Object.values(body.errors).flat().join('<br>') : 'Failed to update profile.');
                            window.showAppToast(errMsg, 'error');
                        }
                    })
                    .catch(() => {
                        submitBtn.disabled = false;
                        submitBtn.textContent = 'Save Profile Changes';
                        window.showAppToast('An unexpected server error occurred.', 'error');
                    });
                });
            }

            // Handle Password Form Submit
            const passwordForm = document.getElementById('globalPasswordForm');
            if (passwordForm) {
                passwordForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const newPass = document.getElementById('modalNewPassword').value;
                    const confirmPass = document.getElementById('modalConfirmPassword').value;

                    if (newPass !== confirmPass) {
                        window.showAppToast('New password confirmation does not match.', 'error');
                        return;
                    }

                    const submitBtn = document.getElementById('btnSubmitModalPassword');
                    submitBtn.disabled = true;
                    submitBtn.textContent = 'Updating...';

                    fetch("{{ route('profile.password') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            current_password: document.getElementById('modalCurrentPassword').value,
                            password: newPass,
                            password_confirmation: confirmPass
                        })
                    })
                    .then(res => res.json().then(data => ({ status: res.status, body: data })))
                    .then(({ status, body }) => {
                        submitBtn.disabled = false;
                        submitBtn.textContent = 'Update Password';

                        if (status === 200 && body.success) {
                            window.showAppToast(body.message || 'Password changed successfully!');
                            passwordForm.reset();
                            closeProfileModal();
                        } else {
                            const errMsg = body.message || (body.errors ? Object.values(body.errors).flat().join('<br>') : 'Failed to update password.');
                            window.showAppToast(errMsg, 'error');
                        }
                    })
                    .catch(() => {
                        submitBtn.disabled = false;
                        submitBtn.textContent = 'Update Password';
                        window.showAppToast('An unexpected server error occurred.', 'error');
                    });
                });
            }
        });
    </script>
    @yield('scripts')
</body>

</html>
