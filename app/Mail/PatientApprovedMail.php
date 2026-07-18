<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PatientApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $fullName;
    public $password;

    public function __construct(string $fullName, ?string $password = null)
    {
        $this->fullName = $fullName;
        $this->password = $password;
    }

    public function build()
    {
        return $this->from(config('mail.from.address'), config('mail.from.name'))
            ->replyTo(config('mail.from.address'))
            ->subject('Your BicolVax account is approved')
            ->view('emails.patient_approved')
            ->text('emails.patient_approved_plain');
    }
}
