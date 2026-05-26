<!DOCTYPE html>
<html>
<head>
    <title>OTP Verification</title>
</head>
<body style="font-family: Arial, sans-serif;">
    <h2>Email Verification</h2>
    
    <p>Hello {{ $userName }},</p>
    
    <p>Your One-Time Password (OTP) for UniBridge email verification is:</p>
    
    <div style="background: #f0f0f0; padding: 15px; border-radius: 5px; text-align: center; margin: 20px 0;">
        <h1 style="margin: 0; letter-spacing: 5px; color: #333;">{{ $otp }}</h1>
    </div>
    
    <p><strong>This OTP will expire in 10 minutes.</strong></p>
    
    <p>If you did not request this, please ignore this email.</p>
    
    <p>Thanks,<br>
    {{ config('app.name') }}</p>
</body>
</html>
