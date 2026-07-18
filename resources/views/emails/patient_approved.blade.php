<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Your BicolVax account</title>
</head>
<body>
    <p>Hello {{ $fullName }},</p>

    <p>Your registration has been approved by the admin. You can sign in at <a href="{{ config('app.url') }}">{{ config('app.url') }}</a> using your email address.</p>

    @if(!empty($password))
        <p style="font-size:1.1rem"><strong>Temporary password: {{ $password }}</strong></p>
        <p>Please change your password immediately after logging in for security.</p>
    @else
        <p>Please sign in using the password you created during registration. If you forgot your password, use the password reset flow.</p>
    @endif

    <p>If you didn't register for this account, please ignore this email.</p>

    <p>For security, please change your password after your first login.</p>

    <p>Regards,<br>BicolVax Team</p>
</body>
</html>
