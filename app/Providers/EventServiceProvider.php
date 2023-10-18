<?php

namespace App\Providers;

use App\Listeners\AchatSubscriber;
use App\Listeners\ContratSubscriber;
use App\Listeners\PaiementSubscriber;
use App\Listeners\VisiteSubscriber;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

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
    ];
    /**
     * The subscriber classes to register.
     * @var array
     */
    protected $subscribe = [
        AchatSubscriber::class,
        ContratSubscriber::class,
        PaiementSubscriber::class,
        VisiteSubscriber::class,
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
