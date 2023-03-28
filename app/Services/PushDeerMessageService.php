<?php

namespace App\Services;

use App\Events\SendNotificationEvent;
use App\Http\ReturnCode;
use App\Models\PushDeerDevice;
use App\Models\PushDeerKey;
use App\Models\PushDeerMessage;
use App\Models\PushDeerUser;
use App\Models\QueueExchangeEnum;
use App\Models\RoutingKeyEnum;
use Hhxsv5\LaravelS\Swoole\Task\Event;
use Illuminate\Support\Facades\Auth;
use Str;

class PushDeerMessageService
{
    private ?PushDeerUser $user;

    public function __construct(public readonly MessageService $message)
    {
        $this->user = Auth::getUser();
    }

    public function list(array $data): array
    {
        $limit = min($data['limit'] ?? 10, 100);
        $since = $data['since_id'] ?? 0;

        return PushDeerMessage::whereUid($this->user?->id)
            ->select(['id', 'uid', 'text', 'desp', 'type', 'pushkey_name', 'created_at'])
            ->where('id', '>', $since)
            ->orderBy('id', 'desc')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    public function push(array $data): void
    {
        $pushKeys = collect(explode(',', $data['pushkey']));
        $pushKeys->unique()->splice(config('services.limit.message.push'));

        $keys = PushDeerKey::whereIn('key', $pushKeys)->get();
        $result = [];

        $queueMessages = [];
        $queueMessageCount = 0;
        $keys->each(function (PushDeerKey $key) use (&$data, &$queueMessages, &$queueMessageCount) {
            if ($key->userDevices->count() < 1) {
                $this->message->addMessage(ReturnCode::ARGS, $key->name . ' 没有可用的设备，请先注册');
            }

            $message = new PushDeerMessage();
            $message->uid = $key->uid;
            $message->text = $data['text'] ?? '';
            $message->desp = $data['desp'] ?? '';
            $message->type = $data['type'] ?? 'markdown';
            $message->readkey = Str::random(32);
            $message->pushkey_name = $key->name;
            $message->save();

            $message->text = $message->type == 'image' ? '[图片]' : $message->text;
            $key->userDevices->each(function (PushDeerDevice $device) use (&$queueMessages, $message, &$queueMessageCount) {
                // 方式一：异步事件，Swoole进程通信
                 Event::fire((new SendNotificationEvent($device, $message))->setTries(3));
                $queueMessageCount += 1;
//                $routingKey = match ($device->type) {
//                    'ios'     => $device->is_clip ? RoutingKeyEnum::NOTIFICATION_APN_CLIP : RoutingKeyEnum::NOTIFICATION_APN_APP,
//                    'android' => $device->is_clip ? RoutingKeyEnum::NOTIFICATION_FCM_CLIP : RoutingKeyEnum::NOTIFICATION_FCM_APP,
//                };
//                $queueMessages[$routingKey->value][] = [
//                    'device'  => $device,
//                    'message' => $message,
//                ];
            });
        });

        // 方式二：异步队列，RabbitMQ
//        foreach ($queueMessages as $routingKey => $items) {
//            $queueMessageCount += count($items);
//            app(QueueService::class)->publish(
//                data: $items,
//                exchange: QueueExchangeEnum::NOTIFICATION_EXCHANGE,
//                routingKey: RoutingKeyEnum::from($routingKey)
//            );
//        }

        $result[] = json_encode([
            'counts'  => $queueMessageCount,
            'logs'    => [],
            'success' => 'ok',
        ]);

        $this->message->setData($result);
    }

    public function remove(array $data = []): bool
    {
        if (empty($data)) {
            return $this->user->userMessages()->delete();
        }

        return PushDeerMessage::whereUid($this->user?->id)
            ->where('id', '=', $data['id'])
            ->delete();
    }
}