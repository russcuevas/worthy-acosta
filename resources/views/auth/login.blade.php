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
                    <p class="auth-role-desc" id="roleDisplayLabel">Administrator Portal Access</p>
                </div>

                <form action="{{ route('authenticate') }}" method="POST" class="auth-form-body">
                    @csrf
                    <input type="hidden" name="role" id="roleInput" value="admin">

                    <div class="form-group">
                        <label class="form-label" for="email">Username or Email Address</label>
                        <input type="text" id="email" name="email" class="form-input-bordered"
                            placeholder="admin@worthyacosta.ph" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password">Password</label>
                        <input type="password" id="password" name="password" class="form-input-bordered"
                            placeholder="••••••••" required>
                    </div>

                    <button type="submit" class="btn-signin">
                        SIGN IN
                    </button>

                    <div class="auth-links-row">
                        <a href="#" class="auth-sublink"></a>
                        <a href="#" class="auth-sublink">Forgot Password?</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
</body>

</html>
