<?php

namespace App\Services\Push;

use App\Models\PushDeerDevice;
use App\Models\PushDeerMessage;
use PhpAmqpLib\Message\AMQPMessage;

interface PushServiceInterface
{
    /**
     * @param PushDeerDevice $device
     * @param PushDeerMessage $message
     * @param int $tries
     * @return bool
     */
    public function send(PushDeerDevice $device, PushDeerMessage $message, int $tries = 1): bool;


    /**
     * @param array<array{device: PushDeerDevice, message: PushDeerMessage}> $packages
     * @param int $tries
     * @return bool
     */
    public function sendBatch(array $packages, int $tries = 1): bool;
}