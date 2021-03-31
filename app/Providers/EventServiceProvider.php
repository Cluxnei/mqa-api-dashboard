<?php

namespace App\Providers;

use App\Events\CompanyActivated;
use App\Events\CompanyCreated;
use App\Events\CompanyInactivated;
use App\Events\UserActivated;
use App\Events\UserEmailVerification;
use App\Events\UserInactivated;
use App\Events\UserRegistered;
use App\Listeners\CompanyStatus;
use App\Listeners\SendEmailVerificationCode;
use App\Listeners\UserStatusChange;
use App\Listeners\UserWelcome;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        UserEmailVerification::class => [
            SendEmailVerificationCode::class,
        ],
        UserRegistered::class => [
            UserWelcome::class,
        ],
        UserActivated::class => [
            UserStatusChange::class,
        ],
        UserInactivated::class => [
            UserStatusChange::class,
        ],
        CompanyCreated::class => [
            CompanyStatus::class,
        ],
        CompanyActivated::class => [
            CompanyStatus::class,
        ],
        CompanyInactivated::class => [
            CompanyStatus::class,
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
