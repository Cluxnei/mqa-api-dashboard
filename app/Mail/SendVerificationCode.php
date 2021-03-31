<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendVerificationCode extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public string $verificationCode;

    /**
     * Create a new message instance.
     *
     * @param string $verificationCode
     */
    public function __construct(string $verificationCode)
    {
        $this->verificationCode = $verificationCode;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): SendVerificationCode
    {
        return $this->view('mails.send-verification-code');
    }
}
