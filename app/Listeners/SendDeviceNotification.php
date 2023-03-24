<?php

namespace App\Listeners;

use App\Events\DeviceNotificationProcessed;
use App\Services\Push\ApnAppService;
use App\Services\Push\ApnClipService;
use App\Services\Push\FcmAppService;
use App\Services\Push\FcmClipService;
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
            'ios'     => $device->is_clip ? ApnClipService::class : ApnAppService::class,
            'android' => $device->is_clip ? FcmClipService::class : FcmAppService::class,
        };
        app($channel)->send($device, $message);
    }
}
