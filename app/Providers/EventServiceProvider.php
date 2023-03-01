<?php

namespace App\Providers;

use App\Events\DeviceNotificationProcessed;
use App\Listeners\SendDeviceNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Laravel\Telescope\Telescope;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot(): void
    {
        if ($this->app->isLocal()) {
            Event::listen('laravels.received_request', function ($request, $app) {
                $reflection = new \ReflectionClass(Telescope::class);
                $handlingApprovedRequest = $reflection->getMethod('handlingApprovedRequest');
                $handlingApprovedRequest->setAccessible(true);
                $handlingApprovedRequest->invoke(null, $app) ? Telescope::startRecording() : Telescope::stopRecording();
            });
        }
    }
}
