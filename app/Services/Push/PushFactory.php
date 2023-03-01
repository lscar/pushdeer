<?php

namespace App\Services\Push;

use App\Models\PushDeerDevice;
use App\Models\PushDeerMessage;

class PushFactory implements PushService
{
    private PushService $service;

    public function __construct(PushService $service)
    {
        $this->service = $service;
    }


    public function send(PushDeerDevice $device, PushDeerMessage $message, int $tries = 1): bool
    {
        return $this->service->send($device, $message, $tries);
    }
}