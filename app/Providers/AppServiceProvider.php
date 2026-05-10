<?php

namespace App\Providers;

use App\Contracts\DistanceCalculatorInterface;
use App\Contracts\NotificationServiceInterface;
use App\Contracts\PaymentGatewayInterface;
use App\Events\OrderCreated;
use App\Events\OrderStatusChanged;
use App\Events\PaymentProcessed;
use App\Events\RiderAssigned;
use App\Listeners\DispatchRiderOnOrder;
use App\Listeners\NotifyOrderStatusChange;
use App\Listeners\ProcessPayoutOnDelivery;
use App\Services\GoogleMapsDistanceCalculator;
use App\Services\PaymobPaymentGateway;
use App\Services\External\PlaceholderNotificationService;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind external service contracts to placeholder implementations.
        // Replace these bindings when integrating real services.
        $this->app->bind(DistanceCalculatorInterface::class, GoogleMapsDistanceCalculator::class);
        $this->app->bind(PaymentGatewayInterface::class, PaymobPaymentGateway::class);
        $this->app->bind(NotificationServiceInterface::class, PlaceholderNotificationService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Event → Listener mappings
        Event::listen(OrderStatusChanged::class, NotifyOrderStatusChange::class);
        Event::listen(OrderStatusChanged::class, DispatchRiderOnOrder::class);
        Event::listen(OrderStatusChanged::class, ProcessPayoutOnDelivery::class);

        // OrderCreated listeners
        Event::listen(OrderCreated::class, NotifyOrderStatusChange::class);

        // RiderAssigned — no additional listeners needed (handled via broadcast)

        // PaymentProcessed — no additional listeners needed (handled via broadcast)
    }
}
