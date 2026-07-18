Hello {{ $fullName }},

Your registration has been approved by the admin. You can sign in at {{ config('app.url') }} using your email address.

@if(!empty($password))
Temporary password: {{ $password }}

Please change your password immediately after logging in for security.
@else
Please sign in using the password you created during registration. If you forgot your password, use the password reset flow.
@endif

If you didn't register for this account, ignore this message.

Regards,
BicolVax Team
