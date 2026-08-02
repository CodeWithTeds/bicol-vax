<?php

namespace App\Mail;

use App\Models\ScheduledReminder;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ScheduledReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public ScheduledReminder $reminder)
    {
    }

    public function build()
    {
        return $this->from(config('mail.from.address'), config('mail.from.name'))
            ->replyTo(config('mail.from.address'))
            ->subject('BicolVax vaccination reminder: ' . $this->reminder->dose_label)
            ->view('emails.scheduled_reminder')
            ->text('emails.scheduled_reminder_plain');
    }
}
