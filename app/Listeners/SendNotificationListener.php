<?php

namespace App\Listeners;

use App\Events\SendNotificationEvent;
use App\Services\Push\ApnAppService;
use App\Services\Push\ApnClipService;
use App\Services\Push\FcmAppService;
use App\Services\Push\FcmClipService;
use App\Services\Push\PushService;
use Hhxsv5\LaravelS\Swoole\Task\Event;
use Hhxsv5\LaravelS\Swoole\Task\Listener;

class SendNotificationListener extends Listener
{
    /**
     * Handle the event.
     *
     * @param SendNotificationEvent $event
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

        app(PushService::class, ['service' => app($channel)])->send($device, $message);
    }
}
