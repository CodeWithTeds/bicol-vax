<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>OTP</title>
</head>
<body>
    <p>Hi,</p>
    <p>Your OTP code for admin login is:</p>
    <h2>{{ $code }}</h2>
    <p>This code expires at {{ $expiresAt->toDateTimeString() }}.</p>
    <p>If you did not request this, ignore this email.</p>
    <p>— BicolVax</p>
</body>
</html>
