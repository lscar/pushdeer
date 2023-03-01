<?php

namespace App\Services\Push;

use App\Models\PushDeerDevice;
use App\Models\PushDeerMessage;

interface PushService
{
    public function send(PushDeerDevice $device, PushDeerMessage $message, int $tries = 1): bool;
}