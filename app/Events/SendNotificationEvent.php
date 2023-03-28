<?php

namespace App\Events;

use App\Listeners\SendNotificationListener;
use App\Models\PushDeerDevice;
use App\Models\PushDeerMessage;
use Hhxsv5\LaravelS\Swoole\Task\Event;

class SendNotificationEvent extends Event
{
    private PushDeerDevice $device;
    private PushDeerMessage $message;

    protected $listeners = [
        // 监听器列表
        SendNotificationListener::class,
    ];

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(PushDeerDevice $device, PushDeerMessage $message)
    {
        $this->device = $device;
        $this->message = $message;
    }

    /**
     * @return PushDeerDevice
     */
    public function getDevice(): PushDeerDevice
    {
        return $this->device;
    }

    /**
     * @param PushDeerDevice $device
     */
    public function setDevice(PushDeerDevice $device): void
    {
        $this->device = $device;
    }

    /**
     * @return PushDeerMessage
     */
    public function getMessage(): PushDeerMessage
    {
        return $this->message;
    }

    /**
     * @param PushDeerMessage $message
     */
    public function setMessage(PushDeerMessage $message): void
    {
        $this->message = $message;
    }

}
