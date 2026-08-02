<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Vaccination Reminder</title>
</head>
<body style="font-family: Arial, sans-serif; color: #1f2f2f; line-height: 1.6;">
    <h2 style="color: #2b8f90;">BicolVax Vaccination Reminder</h2>
    <p>Hello {{ $reminder->patient_name }},</p>
    <p>This is a reminder for your <strong>{{ $reminder->dose_label }}</strong>.</p>
    <p>
        Schedule:
        <strong>{{ optional($reminder->reminder_date)->format('M d, Y') }}</strong>
        @if($reminder->reminder_time)
            at <strong>{{ $reminder->reminder_time }}</strong>
        @endif
    </p>
    <p>Please visit the clinic on your scheduled date so you do not miss your vaccination dose.</p>
    <p>Thank you,<br>BicolVax Clinic</p>
</body>
</html>
