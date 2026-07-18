<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $code;
    public $expiresAt;
    public $subjectLine;

    public function __construct(string $code, Carbon $expiresAt, ?string $subjectLine = null)
    {
        $this->code = $code;
        $this->expiresAt = $expiresAt;
        $this->subjectLine = $subjectLine ?: 'Your BicolVax admin login OTP';
    }

    public function build()
    {
        return $this->subject($this->subjectLine)
            ->view('emails.otp');
    }
}
