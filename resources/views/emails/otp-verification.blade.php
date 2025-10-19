<!DOCTYPE html>
<html>
<head>
    <title>Email Verification - Pertamina</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: #003366;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .logo {
            max-width: 150px;
            height: auto;
        }
        .content {
            padding: 30px;
        }
        .otp-code {
            font-size: 32px;
            font-weight: bold;
            color: #003366;
            text-align: center;
            letter-spacing: 5px;
            margin: 30px 0;
            padding: 15px;
            background: #f0f8ff;
            border-radius: 5px;
            border: 2px dashed #003366;
        }
        .verify-button {
            display: inline-block;
            background: #003366;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin: 20px 0;
            text-align: center;
        }
        .verify-button:hover {
            background: #002244;
        }
        .footer {
            background: #f4f4f4;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        .warning {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ url('images/logo-pertamina.png') }}" alt="Pertamina Logo" class="logo">
            <h1>PT KILANG PERTAMINA INTERNASIONAL</h1>
        </div>

        <div class="content">
            <h2>Hello {{ $userName }},</h2>

            <p>Thank you for registering with our CCTV Monitoring System. To complete your registration, please use the following verification code:</p>

            <div class="otp-code">{{ $otp }}</div>

            <p>This code will expire in 5 minutes. If you didn't request this verification, please ignore this email.</p>

            <div style="text-align: center;">
                <a href="{{ url('/') }}" class="verify-button">Verify Your Email</a>
            </div>

            <div class="warning">
                <strong>Security Notice:</strong> Never share this code with anyone. Pertamina will never ask for this code via phone or email.
            </div>
        </div>

        <div class="footer">
            <p>PT Kilang Pertamina Internasional Refinery Unit VI Balongan</p>
            <p>This is an automated message, please do not reply to this email.</p>
            <p>&copy; 2025 Pertamina. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
