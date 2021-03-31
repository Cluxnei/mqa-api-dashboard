<?php

namespace App\Listeners;

use App\Events\UserEmailVerification;
use App\Mail\SendVerificationCode;
use App\Models\VerificationCode;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendEmailVerificationCode implements ShouldQueue
{
    public function __construct()
    {
    }

    public function handle(UserEmailVerification $event): void
    {
        VerificationCode::query()->create([
            'email' => $event->email,
            'code' => $event->code,
            'expires_at' => now()->addHour(),
        ]);
        Mail::to($event->email)->queue(new SendVerificationCode($event->code));
    }
}
