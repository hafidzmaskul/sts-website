<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Welcome to {{ config('app.name') }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f4f5f7;
            color: #333333;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }
        .wrapper {
            width: 100%;
            background-color: #f4f5f7;
            padding: 40px 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }
        .header {
            background-color: #1e293b;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .content {
            padding: 40px 30px;
            line-height: 1.6;
        }
        .content h2 {
            margin-top: 0;
            color: #0f172a;
            font-size: 20px;
        }
        .credentials-card {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 20px;
            margin: 25px 0;
        }
        .credentials-row {
            margin-bottom: 10px;
            font-size: 15px;
        }
        .credentials-row:last-child {
            margin-bottom: 0;
        }
        .label {
            font-weight: 600;
            color: #64748b;
            display: inline-block;
            width: 100px;
        }
        .value {
            color: #0f172a;
            font-family: SFMono-Regular, Consolas, 'Liberation Mono', Menlo, monospace;
            background-color: #f1f5f9;
            padding: 2px 6px;
            border-radius: 4px;
        }
        .btn {
            display: inline-block;
            background-color: #2563eb;
            color: #ffffff !important;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 6px;
            font-weight: 500;
            margin: 20px 0;
            text-align: center;
        }
        .btn:hover {
            background-color: #1d4ed8;
        }
        .footer {
            background-color: #f8fafc;
            padding: 20px 30px;
            text-align: center;
            font-size: 13px;
            color: #64748b;
            border-top: 1px solid #e2e8f0;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <div class="header">
                <h1>{{ config('app.name') }}</h1>
            </div>
            <div class="content">
                <h2>Account Approved!</h2>
                <p>Hello {{ $user->name }},</p>
                <p>We are pleased to inform you that your application has been approved. Your trade/customer account is now active and ready for use.</p>
                
                <p>Below are your account login credentials:</p>
                
                <div class="credentials-card">
                    <div class="credentials-row">
                        <span class="label">Email:</span>
                        <span class="value">{{ $user->email }}</span>
                    </div>
                    <div class="credentials-row">
                        <span class="label">Password:</span>
                        <span class="value">{{ $password }}</span>
                    </div>
                </div>

                <p>Please log in and update your password as soon as possible for security reasons.</p>
                
                <div style="text-align: center;">
                    <a href="{{ url('/login-page') }}" class="btn" target="_blank">Log In to Your Account</a>
                </div>

                <p>Thank you for choosing {{ config('app.name') }}!</p>
            </div>
            <div class="footer">
                <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>
