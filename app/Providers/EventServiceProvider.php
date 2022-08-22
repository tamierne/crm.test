<?php

namespace App\Providers;

use App\Events\UrlParserFinished;
use App\Events\UrlParserStarted;
use App\Listeners\SendFinishedParsingSuperAdminNotification;
use App\Listeners\SendFinishedParsingUserNotification;
use App\Listeners\SendStartParsingSuperAdminNotification;
use App\Listeners\SendStartParsingUserNotification;
use App\Models\Project;
use App\Models\Task;
use App\Observers\ProjectObserver;
use App\Observers\TaskObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        UrlParserStarted::class => [
            SendStartParsingUserNotification::class,
            SendStartParsingSuperAdminNotification::class,
        ],
        UrlParserFinished::class => [
            SendFinishedParsingUserNotification::class,
            SendFinishedParsingSuperAdminNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Task::observe(TaskObserver::class);
        Project::observe(ProjectObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
