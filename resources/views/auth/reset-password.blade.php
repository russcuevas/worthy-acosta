<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Worthy Acosta</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        .btn-loading {
            position: relative;
            pointer-events: none;
            opacity: 0.85;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .spinner-icon {
            width: 18px;
            height: 18px;
            border: 2.5px solid rgba(255, 255, 255, 0.35);
            border-top-color: #FFFFFF;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>
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
                <p class="banner-brand-subtitle">OFFICIAL SERVICES & ELECTORAL PORTAL</p>
            </div>

            <div class="banner-footer">
                <span>&copy; {{ date('Y') }} Worthy Acosta</span>
            </div>
        </div>

        <!-- Right Form Panel -->
        <div class="split-auth-form-panel">
            <div class="split-auth-form-wrapper">
                <div class="form-header">
                    <h2 class="auth-heading">Set <span class="highlight-brand">New Password</span></h2>
                    <p class="auth-role-desc">Create a strong, new password to access your account</p>
                </div>

                <!-- Reset Password Form -->
                <form action="{{ route('password.update') }}" method="POST" class="auth-form-body" id="resetPasswordForm">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="form-group">
                        <label class="form-label" for="email">Email Address</label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email', $email) }}" 
                               class="form-input-bordered @error('email') is-invalid @enderror" 
                               readonly
                               style="background-color: #F8FAFC; color: #475569; cursor: not-allowed;"
                               required>
                        @error('email')
                            <span class="text-danger" style="font-size: 0.78rem; color: #EF4444; margin-top: 4px; display: block;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password">New Password</label>
                        <div class="password-input-wrap">
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   class="form-input-bordered @error('password') is-invalid @enderror"
                                   placeholder="At least 6 characters" 
                                   required 
                                   autofocus
                                   autocomplete="new-password">
                            <button type="button" class="btn-toggle-password" id="btnToggleNewPassword" aria-label="Toggle password visibility">
                                <svg id="eyeIconNew" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <span class="text-danger" style="font-size: 0.78rem; color: #EF4444; margin-top: 4px; display: block;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password_confirmation">Confirm New Password</label>
                        <div class="password-input-wrap">
                            <input type="password" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   class="form-input-bordered"
                                   placeholder="Re-type your new password" 
                                   required 
                                   autocomplete="new-password">
                            <button type="button" class="btn-toggle-password" id="btnToggleConfirmPassword" aria-label="Toggle password visibility">
                                <svg id="eyeIconConfirm" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn-signin" id="btnResetSubmit">
                        <span class="btn-text">RESET PASSWORD</span>
                    </button>

                    <div class="auth-remember-row" style="justify-content: center; margin-top: 18px;">
                        <a href="{{ route('login') }}" class="auth-sublink" style="display: inline-flex; align-items: center; gap: 6px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                            </svg>
                            Return to Sign in
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Floating Toast Notification -->
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
        // Dismiss toast helper
        function dismissToast(el) {
            const toast = el.closest ? el.closest('.bottom-toast') : el;
            if (!toast) return;
            toast.classList.add('hide');
            setTimeout(() => { toast.remove(); }, 350);
        }

        document.addEventListener('DOMContentLoaded', () => {
            const toast = document.getElementById('authToast');
            if (toast) {
                setTimeout(() => { dismissToast(toast); }, 4000);
            }
        });

        // Toggle New Password Visibility
        const btnToggleNewPassword = document.getElementById('btnToggleNewPassword');
        const passwordInput = document.getElementById('password');
        const eyeIconNew = document.getElementById('eyeIconNew');
        let newPasswordVisible = false;

        if (btnToggleNewPassword && passwordInput && eyeIconNew) {
            btnToggleNewPassword.addEventListener('click', () => {
                newPasswordVisible = !newPasswordVisible;
                passwordInput.type = newPasswordVisible ? 'text' : 'password';
                eyeIconNew.innerHTML = newPasswordVisible
                    ? '<path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />'
                    : '<path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />';
            });
        }

        // Toggle Confirm Password Visibility
        const btnToggleConfirmPassword = document.getElementById('btnToggleConfirmPassword');
        const confirmPasswordInput = document.getElementById('password_confirmation');
        const eyeIconConfirm = document.getElementById('eyeIconConfirm');
        let confirmPasswordVisible = false;

        if (btnToggleConfirmPassword && confirmPasswordInput && eyeIconConfirm) {
            btnToggleConfirmPassword.addEventListener('click', () => {
                confirmPasswordVisible = !confirmPasswordVisible;
                confirmPasswordInput.type = confirmPasswordVisible ? 'text' : 'password';
                eyeIconConfirm.innerHTML = confirmPasswordVisible
                    ? '<path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />'
                    : '<path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />';
            });
        }

        // Form Submit Loading Spinner
        const resetPasswordForm = document.getElementById('resetPasswordForm');
        const btnResetSubmit = document.getElementById('btnResetSubmit');

        if (resetPasswordForm && btnResetSubmit) {
            resetPasswordForm.addEventListener('submit', function(e) {
                if (btnResetSubmit.disabled) {
                    e.preventDefault();
                    return;
                }
                btnResetSubmit.disabled = true;
                btnResetSubmit.classList.add('btn-loading');
                btnResetSubmit.innerHTML = `
                    <div class="spinner-icon"></div>
                    <span>UPDATING PASSWORD...</span>
                `;
            });
        }
    </script>
</body>

</html>
