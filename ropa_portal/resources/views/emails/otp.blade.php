<!DOCTYPE html>
<html>
<head>
    <title>Email Verification OTP</title>
</head>
<body style="font-family: Arial, sans-serif;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <div style="text-align: center; margin-bottom: 30px;">
            <h2 style="color: #153d6f;">RoPA Portal - Email Verification</h2>
        </div>

        <p>Dear {{ $name }},</p>

        <p>Thank you for registering with the University of Ghana RoPA Portal. Please use the following One-Time Password (OTP) to verify your email address:</p>

        <div style="text-align: center; margin: 30px 0;">
            <div style="font-size: 32px; font-weight: bold; letter-spacing: 5px; color: #b69964; background: #f5f5f5; padding: 15px; border-radius: 10px; display: inline-block;">
                {{ $otp }}
            </div>
        </div>

        <p>This OTP is valid for <strong>10 minutes</strong>. If you didn't request this verification, please ignore this email.</p>

        <p>For security reasons, never share this OTP with anyone.</p>

        <hr style="margin: 30px 0;">

        <p style="color: #666; font-size: 12px;">
            This is an automated message from the University of Ghana RoPA Portal. Please do not reply to this email.
        </p>
    </div>
</body>
</html>
