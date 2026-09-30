<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset Request - Worthy Acosta</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #F1F5F9;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            color: #334155;
            -webkit-font-smoothing: antialiased;
        }
        .email-wrapper {
            width: 100%;
            background-color: #F1F5F9;
            padding: 36px 16px;
        }
        .email-container {
            max-width: 580px;
            margin: 0 auto;
            background-color: #FFFFFF;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.07);
            border: 1px solid #E2E8F0;
        }
        .email-header {
            background: linear-gradient(135deg, #092C4C 0%, #075998 100%);
            padding: 32px 28px;
            text-align: center;
            color: #FFFFFF;
        }
        .email-brand-title {
            margin: 0;
            font-size: 24px;
            font-weight: 800;
            letter-spacing: 0.5px;
            color: #FFFFFF;
        }
        .email-brand-subtitle {
            margin: 6px 0 0 0;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #93C5FD;
            font-weight: 600;
        }
        .email-body {
            padding: 36px 32px;
            line-height: 1.65;
            font-size: 15px;
            color: #334155;
        }
        .greeting {
            font-size: 18px;
            font-weight: 700;
            color: #0F172A;
            margin-top: 0;
            margin-bottom: 14px;
        }
        .btn-wrapper {
            text-align: center;
            margin: 32px 0;
        }
        .btn-reset {
            display: inline-block;
            background: linear-gradient(135deg, #092C4C 0%, #075998 100%);
            color: #FFFFFF !important;
            text-decoration: none;
            padding: 14px 34px;
            font-size: 15px;
            font-weight: 700;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(7, 89, 152, 0.3);
        }
        .info-box {
            background-color: #F8FAFC;
            border-left: 4px solid #075998;
            padding: 14px 18px;
            border-radius: 6px;
            margin: 24px 0;
            font-size: 13.5px;
            color: #475569;
        }
        .fallback-text {
            font-size: 12.5px;
            color: #64748B;
            word-break: break-all;
            margin-top: 24px;
            padding-top: 20px;
            border-top: 1px solid #E2E8F0;
        }
        .fallback-link {
            color: #075998;
            text-decoration: underline;
        }
        .email-footer {
            background-color: #F8FAFC;
            padding: 24px 32px;
            text-align: center;
            border-top: 1px solid #E2E8F0;
            font-size: 12px;
            color: #94A3B8;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-container">
            <!-- Header -->
            <div class="email-header">
                <h1 class="email-brand-title">Worthy Acosta</h1>
                <p class="email-brand-subtitle">Official Services & Electoral Portal</p>
            </div>

            <!-- Body -->
            <div class="email-body">
                <p class="greeting">Hello {{ $user->name }},</p>
                
                <p>
                    You are receiving this email because we received a password reset request for your account on the <strong>Worthy Acosta Portal</strong>.
                </p>

                <div class="btn-wrapper">
                    <a href="{{ $resetUrl }}" class="btn-reset" target="_blank">
                        Reset Password
                    </a>
                </div>

                <div class="info-box">
                    <strong>Note:</strong> This password reset link is valid for <strong>{{ $expireMinutes }} minutes</strong>. If the link expires, you will need to request a new password reset.
                </div>

                <p style="font-size: 13.5px; color: #64748B; margin-bottom: 0;">
                    If you did not request a password reset, you can safely ignore this email. No changes will be made to your account.
                </p>

                <div class="fallback-text">
                    If you're having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser:<br>
                    <a href="{{ $resetUrl }}" class="fallback-link">{{ $resetUrl }}</a>
                </div>
            </div>

            <!-- Footer -->
            <div class="email-footer">
                &copy; {{ date('Y') }} Office of Councilor Worthy Acosta. All rights reserved.<br>
                This is an automated system notification. Please do not reply directly to this email.
            </div>
        </div>
    </div>
</body>
</html>
