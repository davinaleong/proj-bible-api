<?php

namespace App\Providers;

use App\Events\BookCreated;
use App\Events\BookDeleted;
use App\Events\BookUpdated;
use App\Events\ChapterCreated;
use App\Events\CopyrightCreated;
use App\Events\CopyrightDeleted;
use App\Events\CopyrightUpdated;
use App\Events\TranslationCreated;
use App\Events\TranslationDeleted;
use App\Events\TranslationUpdated;
use App\Events\UserCreated;
use App\Events\UserPasswordUpdated;
use App\Events\UserUpdated;
use App\Listeners\LogBookCreated;
use App\Listeners\LogBookDeleted;
use App\Listeners\LogBookUpdated;
use App\Listeners\LogChapterCreated;
use App\Listeners\LogCopyrightCreated;
use App\Listeners\LogCopyrightDeleted;
use App\Listeners\LogCopyrightUpdated;
use App\Listeners\LogTranslationCreated;
use App\Listeners\LogTranslationDeleted;
use App\Listeners\LogTranslationUpdated;
use App\Listeners\LogUserCreated;
use App\Listeners\LogUserPasswordUpdated;
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
        UserPasswordUpdated::class => [
            LogUserPasswordUpdated::class
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
        ],
        TranslationDeleted::class => [
            LogTranslationDeleted::class
        ],
        BookCreated::class => [
            LogBookCreated::class
        ],
        BookUpdated::class => [
            LogBookUpdated::class
        ],
        BookDeleted::class => [
            LogBookDeleted::class
        ],
        ChapterCreated::class => [
            LogChapterCreated::class
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
