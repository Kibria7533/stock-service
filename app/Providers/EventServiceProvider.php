<?php

namespace App\Providers;

use App\Events\Order\MakeOrderEvent;
use App\Events\Order\StockToPaymentMakeOrderEvent;
use App\Events\Order\StockToUserMakeOrderRollbackEvent;
use App\Listeners\Order\StockToPaymentMakeOrderListener;
use App\Listeners\Order\StockToUserMakeOrderRollbackListener;
use App\Listeners\Order\UserToStockMakeOrderListener;
use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        StockToUserMakeOrderRollbackEvent::class => [
            StockToUserMakeOrderRollbackListener::class
        ],
        StockToPaymentMakeOrderEvent::class => [
            StockToPaymentMakeOrderListener::class
        ]
    ];

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
