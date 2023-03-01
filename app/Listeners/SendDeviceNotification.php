<?php

namespace App\Listeners;

use App\Events\DeviceNotificationProcessed;
use App\Services\Push\ApnService;
use App\Services\Push\FcmService;
use Hhxsv5\LaravelS\Swoole\Task\Event;
use Hhxsv5\LaravelS\Swoole\Task\Listener;

class SendDeviceNotification extends Listener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param DeviceNotificationProcessed $event
     * @return void
     */
    public function handle(Event $event): void
    {
        $message = $event->getMessage();
        $device = $event->getDevice();
        $channel = match ($device->type) {
            'ios'     => ApnService::class,
            'android' => FcmService::class,
        };
        app($channel)->send($device, $message);
    }
}
