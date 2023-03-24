<?php

namespace App\Services\Push;

use App\Models\PushDeerDevice;
use App\Models\PushDeerMessage;

abstract class FcmServiceAbstract implements PushServiceInterface
{

    public function send(PushDeerDevice $device, PushDeerMessage $message, int $tries = 1): bool
    {
        // TODO: Implement send() method.
        return true;
    }

    public function sendBatch(array $packages, int $tries = 1): bool
    {
        // TODO: Implement sendBatch() method.
        return true;
    }
}