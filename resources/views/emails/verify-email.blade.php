<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your SpeedCartel Account</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        .email-wrapper {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .email-header {
            background-color: #e53e3e;
            padding: 20px;
            text-align: center;
        }
        .logo-text {
            color: white;
            font-size: 28px;
            font-weight: bold;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .email-body {
            padding: 30px;
        }
        h1 {
            color: #333;
            margin-top: 0;
            font-size: 24px;
        }
        p {
            margin-bottom: 20px;
        }
        .button {
            display: inline-block;
            background-color: #e53e3e;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            margin: 20px 0;
        }
        .button:hover {
            background-color: #c53030;
        }
        .email-footer {
            background-color: #f7f7f7;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        .verification-code {
            background-color: #f7f7f7;
            padding: 15px;
            border-radius: 5px;
            font-family: monospace;
            font-size: 18px;
            text-align: center;
            margin: 20px 0;
            letter-spacing: 2px;
        }
        .help-text {
            font-size: 14px;
            color: #666;
        }
        .red-text {
            color: #e53e3e;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-header">
            <div class="logo-text">SpeedCartel</div>
        </div>
        
        <div class="email-body">
            <h1>Verify Your Email Address</h1>
            
            <p>Hi {{ $name }},</p>
            
            <p>Thanks for signing up for SpeedCartel! Please verify your email address to get started.</p>
            
            <p>
                <a href="{{ $url }}" class="button">Verify Email Address</a>
            </p>
            
            <p class="help-text">If the button above doesn't work, copy and paste the following URL into your browser:</p>
            
            <p class="help-text" style="word-break: break-all;">
                {{ $url }}
            </p>
            
            <p>If you did not create an account, no further action is required.</p>
            
            <p>Regards,<br>The SpeedCartel Team</p>
        </div>
        
        <div class="email-footer">
            <p>&copy; {{ date('Y') }} SpeedCartel. All rights reserved.</p>
            <p>This email was sent to {{ $email }}</p>
        </div>
    </div>
</body>
</html> 