@extends('layouts.app')

@section('title', 'User Profile & Security')

@section('styles')
<style>
    .profile-page-container {
        display: flex;
        flex-direction: column;
        gap: 24px;
        max-width: 1100px;
        margin: 0 auto;
    }

    /* Hero Banner */
    .profile-hero-card {
        background: linear-gradient(135deg, var(--color-deep-navy) 0%, var(--color-primary-blue) 100%);
        border-radius: var(--radius-lg);
        padding: 30px;
        color: #FFFFFF;
        box-shadow: 0 10px 25px -5px rgba(7, 89, 152, 0.35);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 20px;
        position: relative;
        overflow: hidden;
    }

    .profile-hero-card::after {
        content: '';
        position: absolute;
        right: -30px;
        top: -40px;
        width: 220px;
        height: 220px;
        background: radial-gradient(circle, rgba(6, 182, 212, 0.25) 0%, rgba(255, 255, 255, 0) 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .profile-hero-user {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .profile-hero-avatar {
        width: 76px;
        height: 76px;
        border-radius: 50%;
        background: linear-gradient(135deg, #06B6D4, #38BDF8);
        border: 3.5px solid rgba(255, 255, 255, 0.85);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        font-weight: 800;
        color: #092C4C;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.25);
    }

    .profile-hero-details h1 {
        font-size: 1.6rem;
        font-weight: 800;
        margin: 0 0 4px 0;
        color: #FFFFFF;
    }

    .profile-hero-details p {
        font-size: 0.90rem;
        color: rgba(255, 255, 255, 0.85);
        margin: 0 0 8px 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .hero-badge-role {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: 9999px;
        font-size: 0.76rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.35);
        color: #FFFFFF;
    }

    /* Grid for Forms */
    .profile-grid-layout {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
    }

    @media (max-width: 900px) {
        .profile-grid-layout {
            grid-template-columns: 1fr;
        }
    }

    .profile-card {
        background: #FFFFFF;
        border-radius: var(--radius-lg);
        border: 1px solid var(--card-border);
        box-shadow: var(--shadow-sm);
        display: flex;
        flex-direction: column;
    }

    .profile-card-header {
        padding: 20px 24px;
        border-bottom: 1px solid #EEF2F6;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .profile-card-header-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: #EFF6FF;
        color: var(--color-primary-blue);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .profile-card-header h2 {
        font-size: 1.15rem;
        font-weight: 800;
        color: var(--color-deep-navy);
        margin: 0;
    }

    .profile-card-header p {
        font-size: 0.82rem;
        color: var(--text-muted);
        margin: 2px 0 0 0;
    }

    .profile-card-body {
        padding: 24px;
        display: flex;
        flex-direction: column;
        gap: 18px;
        flex: 1;
    }

    .profile-form-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .profile-form-group label {
        font-size: 0.82rem;
        font-weight: 700;
        color: var(--color-deep-navy);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .profile-form-group .req {
        color: #DC2626;
    }

    .profile-input-wrap {
        position: relative;
        display: flex;
        align-items: center;
    }

    .profile-input {
        width: 100%;
        background: #F8FAFC;
        border: 1.5px solid #CBD5E1;
        border-radius: 8px;
        padding: 10px 14px;
        font-size: 0.88rem;
        color: var(--color-deep-navy);
        outline: none;
        transition: all var(--transition-fast);
        font-family: inherit;
    }

    .profile-input:focus {
        border-color: var(--color-primary-blue);
        background: #FFFFFF;
        box-shadow: 0 0 0 3px rgba(7, 89, 152, 0.12);
    }

    .profile-input[readonly] {
        background: #F1F5F9;
        color: #64748B;
        cursor: not-allowed;
        border-color: #E2E8F0;
    }

    .btn-toggle-eye {
        position: absolute;
        right: 12px;
        background: transparent;
        border: none;
        color: #64748B;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 4px;
    }

    .btn-toggle-eye:hover {
        color: var(--color-primary-blue);
    }

    .field-hint {
        font-size: 0.74rem;
        color: var(--text-muted);
    }

    .profile-card-footer {
        padding: 18px 24px;
        border-top: 1px solid #EEF2F6;
        background: #FAFBFD;
        border-radius: 0 0 var(--radius-lg) var(--radius-lg);
        display: flex;
        justify-content: flex-end;
    }

    .btn-save-action {
        background: linear-gradient(135deg, var(--color-primary-blue), var(--color-deep-navy));
        color: #FFFFFF;
        border: none;
        border-radius: 8px;
        padding: 10px 22px;
        font-size: 0.88rem;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all var(--transition-fast);
        box-shadow: 0 2px 8px rgba(7, 89, 152, 0.25);
    }

    .btn-save-action:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 14px rgba(7, 89, 152, 0.35);
    }

    .btn-save-action:disabled {
        opacity: 0.65;
        cursor: not-allowed;
        transform: none;
    }

    /* Security Note Box */
    .security-info-box {
        background: #F8FAFC;
        border: 1px solid #E2E8F0;
        border-radius: 8px;
        padding: 12px 14px;
        font-size: 0.80rem;
        color: var(--text-muted);
        line-height: 1.45;
        display: flex;
        gap: 10px;
    }

    .security-info-box svg {
        flex-shrink: 0;
        color: var(--color-primary-blue);
        margin-top: 2px;
    }
</style>
@endsection

@section('content')
<div class="profile-page-container">
    <!-- Hero Banner -->
    <div class="profile-hero-card">
        <div class="profile-hero-user">
            <div class="profile-hero-avatar" id="heroUserAvatar">
                {{ strtoupper(substr($user->name, 0, 2)) }}
            </div>
            <div class="profile-hero-details">
                <h1 id="heroUserName">{{ $user->name }}</h1>
                <p>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                    </svg>
                    <span id="heroUserEmail">{{ $user->email }}</span>
                    <span>&bull;</span>
                    <span>@@<span id="heroUserUsername">{{ $user->username ?? 'user' }}</span></span>
                </p>
                <div>
                    <span class="hero-badge-role">
                        @if($user->role === 'admin')
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                            </svg>
                            Administrator
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                            Assistant Portal
                        @endif
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Forms Grid Layout -->
    <div class="profile-grid-layout">
        <!-- 1. Edit Profile Form -->
        <div class="profile-card">
            <div class="profile-card-header">
                <div class="profile-card-header-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                </div>
                <div>
                    <h2>Edit Profile Information</h2>
                    <p>Update your personal account details and credentials</p>
                </div>
            </div>

            <form id="formPageProfile">
                @csrf
                <div class="profile-card-body">
                    <div class="profile-form-group">
                        <label for="inputProfileName">Full Name <span class="req">*</span></label>
                        <input type="text" id="inputProfileName" name="name" class="profile-input" value="{{ $user->name }}" required>
                    </div>

                    <div class="profile-form-group">
                        <label for="inputProfileUsername">Username <span class="req">*</span></label>
                        <input type="text" id="inputProfileUsername" name="username" class="profile-input" value="{{ $user->username }}" required>
                        <span class="field-hint">Used for signing in to your account.</span>
                    </div>

                    <div class="profile-form-group">
                        <label for="inputProfileEmail">Email Address <span class="req">*</span></label>
                        <input type="email" id="inputProfileEmail" name="email" class="profile-input" value="{{ $user->email }}" required>
                    </div>

                    <div class="profile-form-group">
                        <label>Account Role</label>
                        <input type="text" class="profile-input" value="{{ $user->role === 'admin' ? 'Administrator (Full Access)' : 'Assistant Officer (Forms & Records)' }}" readonly>
                        <span class="field-hint">Your assigned security clearance is strictly managed by system administrators.</span>
                    </div>
                </div>

                <div class="profile-card-footer">
                    <button type="submit" class="btn-save-action" id="btnSaveProfile">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                        <span>Save Profile Changes</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- 2. Change Password Form -->
        <div class="profile-card">
            <div class="profile-card-header">
                <div class="profile-card-header-icon" style="background: #FEF3C7; color: #D97706;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                    </svg>
                </div>
                <div>
                    <h2>Change Password</h2>
                    <p>Ensure your account stays secure by using a strong password</p>
                </div>
            </div>

            <form id="formPagePassword">
                @csrf
                <div class="profile-card-body">
                    <div class="security-info-box">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0-10.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                        </svg>
                        <div>
                            New password must contain at least <strong>6 characters</strong>. You will need your current password to authorize this update.
                        </div>
                    </div>

                    <div class="profile-form-group">
                        <label for="inputCurrentPassword">Current Password <span class="req">*</span></label>
                        <div class="profile-input-wrap">
                            <input type="password" id="inputCurrentPassword" name="current_password" class="profile-input" required autocomplete="current-password">
                            <button type="button" class="btn-toggle-eye" onclick="togglePassVisibility('inputCurrentPassword')" title="Show / Hide Password">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="profile-form-group">
                        <label for="inputNewPassword">New Password <span class="req">*</span></label>
                        <div class="profile-input-wrap">
                            <input type="password" id="inputNewPassword" name="password" class="profile-input" minlength="6" required autocomplete="new-password">
                            <button type="button" class="btn-toggle-eye" onclick="togglePassVisibility('inputNewPassword')" title="Show / Hide Password">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="profile-form-group">
                        <label for="inputConfirmPassword">Confirm New Password <span class="req">*</span></label>
                        <div class="profile-input-wrap">
                            <input type="password" id="inputConfirmPassword" name="password_confirmation" class="profile-input" minlength="6" required autocomplete="new-password">
                            <button type="button" class="btn-toggle-eye" onclick="togglePassVisibility('inputConfirmPassword')" title="Show / Hide Password">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="profile-card-footer">
                    <button type="submit" class="btn-save-action" id="btnSavePassword">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                        <span>Update Password</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function togglePassVisibility(inputId) {
        const input = document.getElementById(inputId);
        if (!input) return;
        input.type = input.type === 'password' ? 'text' : 'password';
    }

    document.addEventListener('DOMContentLoaded', function() {
        const formProfile = document.getElementById('formPageProfile');
        const formPassword = document.getElementById('formPagePassword');

        // Handle Profile Update
        if (formProfile) {
            formProfile.addEventListener('submit', function(e) {
                e.preventDefault();
                const btn = document.getElementById('btnSaveProfile');
                btn.disabled = true;
                btn.innerHTML = '<span>Saving...</span>';

                fetch("{{ route('profile.update') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        name: document.getElementById('inputProfileName').value,
                        username: document.getElementById('inputProfileUsername').value,
                        email: document.getElementById('inputProfileEmail').value
                    })
                })
                .then(res => res.json().then(data => ({ status: res.status, body: data })))
                .then(({ status, body }) => {
                    btn.disabled = false;
                    btn.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                        <span>Save Profile Changes</span>
                    `;

                    if (status === 200 && body.success) {
                        if (typeof showAppToast === 'function') {
                            showAppToast(body.message || 'Profile updated successfully!');
                        } else {
                            alert(body.message || 'Profile updated successfully!');
                        }

                        // Update hero and layout items
                        if (body.user) {
                            document.getElementById('heroUserName').textContent = body.user.name;
                            document.getElementById('heroUserEmail').textContent = body.user.email;
                            document.getElementById('heroUserUsername').textContent = body.user.username;
                            document.getElementById('heroUserAvatar').textContent = body.user.initials;

                            // Update sidebar and topbar if present
                            const sideName = document.querySelector('.sidebar-footer .user-name');
                            if (sideName) sideName.textContent = body.user.name;
                            const sideAvatar = document.querySelector('.sidebar-footer .avatar');
                            if (sideAvatar) sideAvatar.textContent = body.user.initials;
                        }
                    } else {
                        const errMsg = body.message || (body.errors ? Object.values(body.errors).flat().join('<br>') : 'Failed to update profile.');
                        if (typeof showAppToast === 'function') {
                            showAppToast(errMsg, 'error');
                        } else {
                            alert(errMsg);
                        }
                    }
                })
                .catch(() => {
                    btn.disabled = false;
                    btn.innerHTML = '<span>Save Profile Changes</span>';
                    if (typeof showAppToast === 'function') {
                        showAppToast('An unexpected server error occurred.', 'error');
                    } else {
                        alert('An unexpected server error occurred.');
                    }
                });
            });
        }

        // Handle Password Update
        if (formPassword) {
            formPassword.addEventListener('submit', function(e) {
                e.preventDefault();
                const btn = document.getElementById('btnSavePassword');
                const newPass = document.getElementById('inputNewPassword').value;
                const confirmPass = document.getElementById('inputConfirmPassword').value;

                if (newPass !== confirmPass) {
                    if (typeof showAppToast === 'function') {
                        showAppToast('New password confirmation does not match.', 'error');
                    } else {
                        alert('New password confirmation does not match.');
                    }
                    return;
                }

                btn.disabled = true;
                btn.innerHTML = '<span>Updating...</span>';

                fetch("{{ route('profile.password') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        current_password: document.getElementById('inputCurrentPassword').value,
                        password: newPass,
                        password_confirmation: confirmPass
                    })
                })
                .then(res => res.json().then(data => ({ status: res.status, body: data })))
                .then(({ status, body }) => {
                    btn.disabled = false;
                    btn.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                        <span>Update Password</span>
                    `;

                    if (status === 200 && body.success) {
                        if (typeof showAppToast === 'function') {
                            showAppToast(body.message || 'Password changed successfully!');
                        } else {
                            alert(body.message || 'Password changed successfully!');
                        }
                        formPassword.reset();
                    } else {
                        const errMsg = body.message || (body.errors ? Object.values(body.errors).flat().join('<br>') : 'Failed to update password.');
                        if (typeof showAppToast === 'function') {
                            showAppToast(errMsg, 'error');
                        } else {
                            alert(errMsg);
                        }
                    }
                })
                .catch(() => {
                    btn.disabled = false;
                    btn.innerHTML = '<span>Update Password</span>';
                    if (typeof showAppToast === 'function') {
                        showAppToast('An unexpected server error occurred.', 'error');
                    } else {
                        alert('An unexpected server error occurred.');
                    }
                });
            });
        }
    });
</script>
@endsection
