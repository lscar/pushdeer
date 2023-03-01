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
use Pushok\Response;
use Str;

class ApnService implements PushService
{
    public function send(PushDeerDevice $device, PushDeerMessage $message, int $tries = 1): bool
    {
        //https://developer.apple.com/documentation/usernotifications/setting_up_a_remote_notification_server/generating_a_remote_notification
        //https://github.com/edamov/pushok
        $payload = Payload::create()
            ->setAlert(Alert::create()->setTitle($message->text)->setBody($message->desp))
            ->setSound(Sound::create()->setVolume(2.0))
            ->setInterruptionLevel('active')
            ->setMutableContent(true);

        $notification = new Notification($payload, $device->device_id);

        $client = $device->is_clip ? app( ApnClipClient::class) : app(ApnAppClient::class);
        $client->addNotification($notification);
        // todo 批量推送，设定缓冲阈值，getNotifications计数判断Push + 定时任务Push
        $try = 0;
        do {
            $try++;
            try {
                $responses = $client->push();
            } catch (Exception $e) {
                if (!Str::contains($e->getMessage(), 'timed out')) {
                    Log::warning($e->getMessage(), $e->getTrace());
                }
            }
        } while ($try < $tries);


        foreach ($responses ?? [] as $response) {
            $log = [
                'deviceToken'      => $response->getDeviceToken(),
                'apnsId'           => $response->getApnsId(),
                'statusCode'       => $response->getStatusCode(),
                'reasonPhrase'     => $response->getReasonPhrase(),
                'errorReason'      => $response->getErrorReason(),
                'errorDescription' => $response->getErrorDescription(),
                '410Timestamp'     => $response->get410Timestamp(),
            ];
            $response->getStatusCode() == Response::APNS_SUCCESS ?
                Log::info('ApnsResponse', $log) : Log::warning('ApnsResponse', $log);
        }

        return empty($responses);
    }
}