<?php

namespace App\Mail;

use App\Models\Company;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendNewCompanyNotificationToAdmin extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Company $company;
    public User $admin;

    /**
     * Create a new message instance.
     *
     * @param Company $company
     * @param User $admin
     */
    public function __construct(Company $company, User $admin)
    {
        $this->company = $company;
        $this->admin = $admin;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): SendNewCompanyNotificationToAdmin
    {
        return $this->view('mails.administrator-new-company-notification');
    }
}
