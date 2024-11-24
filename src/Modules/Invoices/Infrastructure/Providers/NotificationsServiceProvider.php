<?php

declare(strict_types=1);

namespace Modules\Invoices\Infrastructure\Providers;

use Illuminate\Support\Facades\Event;
use Modules\Notifications\Api\Events\ResourceDeliveredEvent;
use Modules\Invoices\Application\Listeners\ResourceDeliveredListener;
use Modules\Invoices\Domain\Services\NotificationServiceInterface;
use Modules\Invoices\Infrastructure\Services\NotificationService;
use Illuminate\Support\ServiceProvider;

class NotificationsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(NotificationServiceInterface::class, NotificationService::class);
    }

    public function boot(): void
    {
        Event::listen(
            ResourceDeliveredEvent::class,
            ResourceDeliveredListener::class,
        );
    }
}
