<?php

namespace App\Listeners;

use App\Events\CompanyActivated;
use App\Events\CompanyCreated;
use App\Events\CompanyInactivated;
use App\Mail\CompanyStatusChange;
use App\Mail\SendNewCompanyNotificationToAdmin;
use App\Models\Company;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class CompanyStatus implements ShouldQueue
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
     * @param CompanyCreated|CompanyActivated|CompanyInactivated $event
     * @return void
     */
    public function handle($event): void
    {
        if ($event instanceof CompanyCreated) {
            $this->companyCreated($event->company);
        }
        if ($event instanceof CompanyActivated || $event instanceof CompanyInactivated) {
            $this->companyStatus($event->company);
        }
    }

    private function companyCreated(Company $company): void
    {
        User::query()->admin()->active()->get()
            ->each(static function (User $admin) use ($company) {
                Mail::to($admin->email)->queue(new SendNewCompanyNotificationToAdmin($company, $admin));
            });
    }

    private function companyStatus(Company $company): void
    {
        $company->users->each(static function (User $user) use ($company) {
            Mail::to($user->email)->queue(new CompanyStatusChange($company, $user));
        });
    }
}
