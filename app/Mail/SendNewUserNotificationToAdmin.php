<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendNewUserNotificationToAdmin extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public User $user;
    public User $admin;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param User $admin
     */
    public function __construct(User $user, User $admin)
    {
        $this->user = $user;
        $this->admin = $admin;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): SendNewUserNotificationToAdmin
    {
        return $this->view('mails.administrator-new-user-notification');
    }
}
