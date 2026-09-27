<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in - Worthy Acosta</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body class="split-auth-body">
    <div class="split-auth-container">
        <!-- Left Brand Banner Panel -->
        <div class="split-auth-banner">
            <div class="banner-top">
                <div class="banner-logo-badge">
                    <img src="{{ asset('images/WA-Logo.png') }}" alt="Worthy Acosta Logo" class="banner-logo-img">
                </div>
            </div>

            <div class="banner-middle">
                <h1 class="banner-brand-title">Worthy Acosta</h1>
                <p class="banner-brand-subtitle">WELCOME TO THE OFFICIAL SERVICES & ELECTORAL PORTAL</p>
            </div>

            <div class="banner-footer">
                <span>&copy; 2026 Worthy Acosta</span>
            </div>
        </div>

        <!-- Right Form Panel -->
        <div class="split-auth-form-panel">
            <div class="split-auth-form-wrapper">
                <div class="form-header">
                    <h2 class="auth-heading">Sign in to <span class="highlight-brand">Worthy Acosta</span></h2>
                    <p class="auth-role-desc">Enter your credentials to access the portal</p>
                </div>

                <!-- Unified Authentication Form -->
                <form action="{{ route('authenticate') }}" method="POST" class="auth-form-body" id="authLoginForm">
                    @csrf

                    <div class="form-group">
                        <label class="form-label" for="email">Username or Email Address</label>
                        <input type="text" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               class="form-input-bordered @error('email') is-invalid @enderror"
                               placeholder="e.g. admin or assistant@worthyacosta.ph" 
                               required 
                               autofocus 
                               autocomplete="username">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password">Password</label>
                        <div class="password-input-wrap">
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   class="form-input-bordered"
                                   placeholder="••••••••" 
                                   required 
                                   autocomplete="current-password">
                            <button type="button" class="btn-toggle-password" id="btnTogglePassword" aria-label="Toggle password visibility">
                                <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="auth-remember-row">
                        <label class="remember-checkbox-label">
                            <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <span>Remember this device</span>
                        </label>
                        <a href="#" class="auth-sublink">Forgot Password?</a>
                    </div>

                    <button type="submit" class="btn-signin" id="btnSubmit">
                        SIGN IN
                    </button>
                </form>

                <!-- Quick Test Credentials Box -->
                <div class="demo-credentials-box">
                    <div class="demo-credentials-title">
                        <span>Test Portals & Accounts</span>
                        <span style="font-weight: 500; font-size: 0.72rem; color: #94A3B8;">Click to autofill</span>
                    </div>
                    <div class="demo-credentials-grid">
                        <div class="demo-chip" onclick="fillCredentials('admin@worthyacosta.ph', 'password')">
                            <div class="demo-chip-role">Administrator Portal</div>
                            <div class="demo-chip-email">admin@worthyacosta.ph</div>
                            <div class="demo-chip-pass">Pass: password &rarr; admin/electoral</div>
                        </div>
                        <div class="demo-chip" onclick="fillCredentials('assistant@worthyacosta.ph', 'password')">
                            <div class="demo-chip-role" style="color: #166534;">Assistant Portal</div>
                            <div class="demo-chip-email">assistant@worthyacosta.ph</div>
                            <div class="demo-chip-pass">Pass: password &rarr; assistant/electoral</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Floating Toast Notification (Bottom Right) -->
    <div class="bottom-toast-container" id="bottomToastContainer">
        @if (session('error'))
            <div class="bottom-toast toast-error" id="authToast">
                <span class="toast-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="#EF4444">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                    </svg>
                </span>
                <span class="toast-text">{{ session('error') }}</span>
                <button type="button" class="toast-close-btn" onclick="dismissToast(this)" aria-label="Close notification">&times;</button>
            </div>
        @elseif (session('success'))
            <div class="bottom-toast toast-success" id="authToast">
                <span class="toast-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="#10B981">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                    </svg>
                </span>
                <span class="toast-text">{{ session('success') }}</span>
                <button type="button" class="toast-close-btn" onclick="dismissToast(this)" aria-label="Close notification">&times;</button>
            </div>
        @elseif (session('info'))
            <div class="bottom-toast toast-info" id="authToast">
                <span class="toast-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="var(--color-wave-cyan)">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                    </svg>
                </span>
                <span class="toast-text">{{ session('info') }}</span>
                <button type="button" class="toast-close-btn" onclick="dismissToast(this)" aria-label="Close notification">&times;</button>
            </div>
        @elseif ($errors->any())
            <div class="bottom-toast toast-error" id="authToast">
                <span class="toast-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="#EF4444">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                    </svg>
                </span>
                <span class="toast-text">{{ $errors->first() }}</span>
                <button type="button" class="toast-close-btn" onclick="dismissToast(this)" aria-label="Close notification">&times;</button>
            </div>
        @endif
    </div>

    <script>
        // Dismiss toast helper with smooth fade-out
        function dismissToast(el) {
            const toast = el.closest ? el.closest('.bottom-toast') : el;
            if (!toast) return;
            toast.classList.add('hide');
            setTimeout(() => {
                toast.remove();
            }, 350);
        }

        // Auto-dismiss the toast after 3.2 seconds
        document.addEventListener('DOMContentLoaded', () => {
            const toast = document.getElementById('authToast');
            if (toast) {
                setTimeout(() => {
                    dismissToast(toast);
                }, 3200);
            }
        });

        // Toggle Password Visibility
        const btnTogglePassword = document.getElementById('btnTogglePassword');
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');
        let passwordVisible = false;

        btnTogglePassword.addEventListener('click', () => {
            passwordVisible = !passwordVisible;
            passwordInput.type = passwordVisible ? 'text' : 'password';
            eyeIcon.innerHTML = passwordVisible
                ? '<path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />'
                : '<path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />';
        });

        // Quick Autofill for Demo Testing
        function fillCredentials(login, pass) {
            const emailInput = document.getElementById('email');
            emailInput.value = login;
            passwordInput.value = pass;
            emailInput.focus();
        }
    </script>
</body>

</html>
