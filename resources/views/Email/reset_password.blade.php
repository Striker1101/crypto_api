<!DOCTYPE html>
<html>

<head>
    <title>Password Reset Request</title>
</head>

<body style="font-family: Arial, sans-serif; line-height: 1.6; background-color: #f9f9f9; padding: 20px;">
    <div
        style="max-width: 600px; margin: auto; background: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);">

        <h2 style="text-align: center; color: #333;">{{ config('app.name') }} - Password Reset</h2>

        <p>Hello,</p>

        <p>We received a request to reset your password for your <strong>{{ config('app.name') }}</strong> account.
            Click the button below to reset your password:</p>

        <p style="text-align: center;">
            <a href="{{ $resetLink }}"
                style="display: inline-block; padding: 12px 18px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px; font-size: 16px; font-weight: bold;">
                Reset Password
            </a>
        </p>

        <p>If you did not request this password reset, please ignore this email. Your account is still secure.</p>

        <p>For security reasons, this link will expire in <strong>3 minutes</strong>.</p>

        <hr style="border: none; border-top: 1px solid #ddd; margin: 20px 0;">

        <p style="font-size: 14px; color: #555;">
            If you have any questions, please contact our support team at
            <a href="mailto:support@{{ config('app.domain') }}" style="color: #007bff;">support@{{ config('app.domain')
                }}</a>.
        </p>

        <p style="font-size: 14px; text-align: center; color: #777;">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </p>

    </div>
</body>

</html>
