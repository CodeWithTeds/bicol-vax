BicolVax Vaccination Reminder

Hello {{ $reminder->patient_name }},

This is a reminder for your {{ $reminder->dose_label }}.

Schedule: {{ optional($reminder->reminder_date)->format('M d, Y') }}@if($reminder->reminder_time) at {{ $reminder->reminder_time }}@endif

Please visit the clinic on your scheduled date so you do not miss your vaccination dose.

Thank you,
BicolVax Clinic
