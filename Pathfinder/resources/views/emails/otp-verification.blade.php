<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin: 0; padding: 0; background-color: #f0f4f8; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f0f4f8; padding: 40px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #13264D 0%, #5AA7C6 100%); padding: 30px 40px; text-align: center;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 24px; font-weight: 700;">Pathfinder</h1>
                            <p style="color: rgba(255,255,255,0.85); margin: 8px 0 0; font-size: 14px;">Career Guidance System</p>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding: 40px;">
                            <h2 style="color: #13264D; margin: 0 0 16px; font-size: 20px;">Hello, {{ $userName }}!</h2>

                            <p style="color: #555; font-size: 15px; line-height: 1.6; margin: 0 0 24px;">
                                Thank you for using <strong>Pathfinder Career Guidance Website</strong>. To complete your registration, please use the verification code below:
                            </p>

                            <!-- OTP Box -->
                            <div style="background-color: #f0f4f8; border: 2px dashed #5AA7C6; border-radius: 10px; padding: 24px; text-align: center; margin: 0 0 24px;">
                                <p style="color: #888; font-size: 13px; margin: 0 0 8px; text-transform: uppercase; letter-spacing: 1px;">Your Verification Code</p>
                                <p style="color: #13264D; font-size: 36px; font-weight: 700; letter-spacing: 8px; margin: 0;">{{ $otp }}</p>
                            </div>

                            <p style="color: #888; font-size: 13px; line-height: 1.5; margin: 0 0 8px;">
                                This code will expire in <strong>10 minutes</strong>. If you did not create an account, please ignore this email.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8fafc; padding: 20px 40px; text-align: center; border-top: 1px solid #e5e7eb;">
                            <p style="color: #aaa; font-size: 12px; margin: 0;">&copy; {{ date('Y') }} Pathfinder Career Guidance. All rights reserved.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
