<?php

namespace App\Services\Push;

use App\Models\PushDeerDevice;
use App\Models\PushDeerMessage;
use Exception;
use Log;
use Pushok\Client;
use Pushok\Notification;
use Pushok\Payload\Alert;
use Pushok\Payload\Sound;
use Pushok\Payload;
use Str;

abstract class ApnServiceAbstract implements PushServiceInterface
{
    abstract protected function getClient(): Client;

    /**
     * @param array<array{device: PushDeerDevice, message: PushDeerMessage}> $packages
     * @return Client
     */
    protected function addNotification(array $packages): Client
    {
        //https://developer.apple.com/documentation/usernotifications/setting_up_a_remote_notification_server/generating_a_remote_notification
        //https://github.com/edamov/pushok

        $client = $this->getClient();
        foreach ($packages as $package) {
            $payload = Payload::create()
                ->setAlert(Alert::create()->setTitle($package['message']->text)->setBody($package['message']->desp))
                ->setSound(Sound::create()->setVolume(2.0))
                ->setInterruptionLevel('active')
                ->setMutableContent(true);
            $notification = new Notification($payload, $package['device']->device_id);
            $client->addNotification($notification);
        }

        return $client;
    }

    /**
     * @param PushDeerDevice $device
     * @param PushDeerMessage $message
     * @param int $tries
     * @return bool
     */
    public function send(PushDeerDevice $device, PushDeerMessage $message, int $tries = 1): bool
    {
        $client = $this->addNotification([
            'message' => $message,
            'device' => $device,
        ]);

        return $this->executor($client, $tries);
    }

    /**
     * @param array<array{device: PushDeerDevice, message: PushDeerMessage}> $packages
     * @param int $tries
     * @return bool
     */
    public function sendBatch(array $packages, int $tries = 1): bool
    {
        $client = $this->addNotification($packages);

        return $this->executor($client, $tries);
    }

    /**
     * @param Client $client
     * @param int $tries
     * @return bool
     */
    protected function executor(Client $client, int $tries): bool
    {
        $try = 0;
        do {
            $try++;
            try {
                $responses = $client->push();
                $try = $tries;
            } catch (Exception $e) {
                if (!Str::contains($e->getMessage(), 'timed out')) {
                    Log::warning($e->getMessage(), $e->getTrace());
                }
            }
        } while ($try < $tries);

        return isset($responses);
    }
}