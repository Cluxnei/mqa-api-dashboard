<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use App\Mail\SendNewUserNotificationToAdmin;
use App\Mail\SendUserWelcomeMail;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class UserWelcome implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param UserRegistered $event
     * @return void
     */
    public function handle(UserRegistered $event): void
    {
        Mail::to($event->user->email)->queue(new SendUserWelcomeMail($event->user));
        User::query()->admin()->active()->get()
            ->each(static function (User $admin) use ($event) {
                Mail::to($admin->email)->queue(new SendNewUserNotificationToAdmin($event->user, $admin));
            });
    }
}
