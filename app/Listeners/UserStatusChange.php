<?php

namespace App\Listeners;

use App\Events\UserActivated;
use App\Events\UserInactivated;
use App\Mail\UserStatusChangeMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class UserStatusChange implements ShouldQueue
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
     * @param UserActivated|UserInactivated $event
     * @return void
     */
    public function handle($event): void
    {
        Mail::to($event->user->email)->queue(new UserStatusChangeMail($event->user));
    }
}
