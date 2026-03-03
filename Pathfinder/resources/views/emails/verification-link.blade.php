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
                                Thank you for using <strong>Pathfinder Career Guidance Website</strong>. Please click the button below to verify your email address and complete your registration.
                            </p>

                            <!-- Verify Button -->
                            <div style="text-align: center; margin: 0 0 24px;">
                                <a href="{{ $verificationUrl }}"
                                   style="display: inline-block; background: linear-gradient(135deg, #5AA7C6 0%, #13264D 100%); color: #ffffff; text-decoration: none; padding: 14px 40px; border-radius: 8px; font-size: 16px; font-weight: 600; letter-spacing: 0.5px;">
                                    Verify Email Address
                                </a>
                            </div>

                            <p style="color: #888; font-size: 13px; line-height: 1.5; margin: 0 0 16px;">
                                This link will expire in <strong>60 minutes</strong>. If you did not create an account, please ignore this email.
                            </p>

                            <p style="color: #aaa; font-size: 12px; line-height: 1.5; margin: 0;">
                                If the button doesn't work, copy and paste this URL into your browser:<br>
                                <span style="color: #5AA7C6; word-break: break-all;">{{ $verificationUrl }}</span>
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
