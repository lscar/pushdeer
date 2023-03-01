<?php

namespace App\Services\Push;

use App\Models\PushDeerDevice;
use App\Models\PushDeerMessage;

class FcmService implements PushService
{

    public function send(PushDeerDevice $device, PushDeerMessage $message, int $tries = 1): bool
    {
        // TODO: Implement send() method.
        return true;
    }
}