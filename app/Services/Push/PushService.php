<?php

namespace App\Services\Push;

use App\Models\PushDeerDevice;
use App\Models\PushDeerMessage;
use PhpAmqpLib\Message\AMQPMessage;

class PushService implements PushServiceInterface
{
    private PushServiceInterface $service;

    public function __construct(PushServiceInterface $service)
    {
        $this->service = $service;
    }

    public function send(PushDeerDevice $device, PushDeerMessage $message, int $tries = 1): bool
    {
        return $this->service->send($device, $message, $tries);
    }

    public function sendBatch(array $packages, int $tries = 1): bool
    {
        return $this->service->sendBatch($packages, $tries);
    }
}