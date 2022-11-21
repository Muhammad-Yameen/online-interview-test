<?php

namespace App\Providers;

use App\Events\CreateOrderEvent;
use App\Events\TransactionStatusUpdateEvent;
use App\Listeners\CreateOrderListener;
use App\Listeners\TransactionStatusUpdateListener;
use App\Listeners\WelcomeEmail;
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
            WelcomeEmail::class
        ],
        CreateOrderEvent::class => [
            CreateOrderListener::class,
        ],
        TransactionStatusUpdateEvent::class => [
            TransactionStatusUpdateListener::class,
        ],
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
