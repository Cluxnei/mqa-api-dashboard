<?php

namespace App\Mail;

use App\Models\Company;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CompanyStatusChange extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Company $company;
    public User $user;

    /**
     * Create a new message instance.
     *
     * @param Company $company
     * @param User $user
     */
    public function __construct(Company $company, User $user)
    {
        $this->company = $company;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): CompanyStatusChange
    {
        return $this->view('mails.company-status-change');
    }
}
