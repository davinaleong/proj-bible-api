<?php

namespace App\Providers;

use App\Events\CopyrightCreated;
use App\Events\CopyrightDeleted;
use App\Events\CopyrightUpdated;
use App\Events\TranslationCreated;
use App\Events\TranslationUpdated;
use App\Events\UserCreated;
use App\Events\UserUpdated;
use App\Listeners\LogCopyrightCreated;
use App\Listeners\LogCopyrightDeleted;
use App\Listeners\LogCopyrightUpdated;
use App\Listeners\LogTranslationCreated;
use App\Listeners\LogTranslationUpdated;
use App\Listeners\LogUserCreated;
use App\Listeners\LogUserUpdated;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

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
        UserCreated::class => [
            LogUserCreated::class
        ],
        UserUpdated::class => [
            LogUserUpdated::class
        ],
        CopyrightCreated::class => [
            LogCopyrightCreated::class
        ],
        CopyrightUpdated::class => [
            LogCopyrightUpdated::class
        ],
        CopyrightDeleted::class => [
            LogCopyrightDeleted::class
        ],
        TranslationCreated::class => [
            LogTranslationCreated::class
        ],
        TranslationUpdated::class => [
            LogTranslationUpdated::class
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
