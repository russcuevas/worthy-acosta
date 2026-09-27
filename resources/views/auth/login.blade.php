<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in - Worthy Acosta</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        .modal-backdrop-custom {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(5px);
            z-index: 10000;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 16px;
        }

        .forgot-pass-dialog {
            background: #FFFFFF;
            border-radius: 16px;
            width: 100%;
            max-width: 490px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35);
            animation: modalFadeIn 0.22s ease-out;
            overflow: hidden;
            border: 1px solid rgba(226, 232, 240, 0.9);
        }

        @keyframes modalFadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .forgot-pass-header {
            padding: 22px 24px;
            background: linear-gradient(135deg, #092C4C, #075998);
            color: #FFFFFF;
            display: flex;
            align-items: center;
            gap: 14px;
            position: relative;
        }

        .forgot-pass-icon-badge {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.15);
            color: #38BDF8;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .under-dev-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 3px 9px;
            border-radius: 9999px;
            font-size: 0.68rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            background: #FEF3C7;
            color: #92400E;
            margin-bottom: 4px;
        }

        .pulse-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #D97706;
            animation: pulseAnim 1.5s infinite;
        }

        @keyframes pulseAnim {
            0% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.4; transform: scale(1.3); }
            100% { opacity: 1; transform: scale(1); }
        }

        .forgot-pass-header h3 {
            margin: 0;
            font-size: 1.15rem;
            font-weight: 800;
            color: #FFFFFF;
        }

        .forgot-pass-close-btn {
            position: absolute;
            top: 18px;
            right: 18px;
            background: rgba(255, 255, 255, 0.15);
            border: none;
            color: #FFFFFF;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            font-size: 1.3rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            line-height: 1;
            transition: all 0.2s ease;
        }

        .forgot-pass-close-btn:hover {
            background: rgba(220, 38, 38, 0.9);
        }

        .forgot-pass-body {
            padding: 22px 24px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .forgot-pass-notice-card {
            background: #FFFBEB;
            border: 1.5px solid #FDE68A;
            border-radius: 10px;
            padding: 14px 16px;
            color: #78350F;
            font-size: 0.84rem;
            line-height: 1.5;
        }

        .notice-card-header {
            display: flex;
            align-items: center;
            gap: 7px;
            font-weight: 800;
            color: #92400E;
            margin-bottom: 6px;
            font-size: 0.86rem;
        }

        .notice-card-subtext {
            margin-top: 8px;
            font-size: 0.74rem;
            color: #B45309;
            opacity: 0.9;
        }

        .forgot-pass-support-info {
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 10px;
            padding: 14px 16px;
        }

        .forgot-pass-form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .forgot-pass-form-group label {
            font-size: 0.80rem;
            font-weight: 700;
            color: #0F172A;
        }

        .forgot-pass-footer {
            padding: 16px 24px;
            border-top: 1px solid #EEF2F6;
            background: #FAFBFD;
            display: flex;
            justify-content: flex-end;
        }

        .btn-forgot-close {
            background: linear-gradient(135deg, var(--color-primary-blue), var(--color-deep-navy));
            color: #FFFFFF;
            border: none;
            padding: 10px 22px;
            border-radius: 8px;
            font-size: 0.86rem;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 2px 6px rgba(7, 89, 152, 0.25);
            transition: all 0.2s ease;
        }

        .btn-forgot-close:hover {
            box-shadow: 0 4px 12px rgba(7, 89, 152, 0.35);
            transform: translateY(-1px);
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

                    <div class="auth-remember-row" style="justify-content: flex-end;">
                        <a href="javascript:void(0)" class="auth-sublink" id="btnForgotPassword">Forgot Password?</a>
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

    <!-- Forgot Password Modal (Under Development) -->
    <div class="modal-backdrop-custom" id="forgotPasswordModalBackdrop" style="display: none;">
        <div class="forgot-pass-dialog">
            <div class="forgot-pass-header">
                <div class="forgot-pass-icon-badge">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z" />
                    </svg>
                </div>
                <div>
                    <div class="under-dev-pill">
                        <span class="pulse-dot"></span>
                        Under Development
                    </div>
                    <h3>Password Recovery</h3>
                </div>
                <button type="button" class="forgot-pass-close-btn" id="btnCloseForgotModal" aria-label="Close modal">&times;</button>
            </div>

            <div class="forgot-pass-body">
                <div class="forgot-pass-notice-card">
                    <div class="notice-card-header">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                        </svg>
                        <span>System Notice / Deployment Note</span>
                    </div>
                    <p style="margin: 0 0 8px 0;">
                        Ang automated password recovery via email ay <strong>kasalukuyang under development</strong>. Magiging fully functional at gagana lamang ito kapag nai-deploy na ang system sa live production server na may naka-configure na official mail delivery service (SMTP).
                    </p>
                    <div class="notice-card-subtext">
                        <em>(This automated reset feature will be activated once deployed to the production environment.)</em>
                    </div>
                </div>

                <div class="forgot-pass-support-info">
                    <div style="font-weight: 700; color: #092C4C; margin-bottom: 6px; font-size: 0.85rem;">
                        Paano ma-access ang iyong account?
                    </div>
                    <div style="font-size: 0.80rem; color: #64748B; line-height: 1.5;">
                        Para sa agarang pag-access o pag-reset ng password, mangyaring makipag-ugnayan sa inyong <strong>System Administrator</strong> o gamitin ang mga nakatalagang testing accounts sa login form.
                    </div>
                </div>

                <div class="forgot-pass-form-group">
                    <label for="recoveryEmail">Your Registered Username or Email</label>
                    <input type="text" id="recoveryEmail" class="form-input-bordered" placeholder="e.g. admin or your-email@worthyacosta.ph" disabled style="background:#F1F5F9; color:#64748B; cursor:not-allowed;">
                    <span style="font-size: 0.72rem; color: #94A3B8;">Automatic recovery links will be sent here upon live deployment.</span>
                </div>
            </div>

            <div class="forgot-pass-footer">
                <button type="button" class="btn-forgot-close" id="btnCancelForgotModal">Naiintindihan (Close)</button>
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

        // Forgot Password Modal Controls
        const forgotModal = document.getElementById('forgotPasswordModalBackdrop');
        const btnForgot = document.getElementById('btnForgotPassword');
        const btnCloseForgot = document.getElementById('btnCloseForgotModal');
        const btnCancelForgot = document.getElementById('btnCancelForgotModal');

        if (btnForgot && forgotModal) {
            btnForgot.addEventListener('click', (e) => {
                e.preventDefault();
                forgotModal.style.display = 'flex';
            });
        }

        function closeForgotModal() {
            if (forgotModal) {
                forgotModal.style.display = 'none';
            }
        }

        if (btnCloseForgot) btnCloseForgot.addEventListener('click', closeForgotModal);
        if (btnCancelForgot) btnCancelForgot.addEventListener('click', closeForgotModal);

        if (forgotModal) {
            forgotModal.addEventListener('click', (e) => {
                if (e.target === forgotModal) {
                    closeForgotModal();
                }
            });
        }
    </script>
</body>

</html>
