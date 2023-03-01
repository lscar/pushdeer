<?php

namespace App\Services;

use App\Events\DeviceNotificationProcessed;
use App\Http\ReturnCode;
use App\Models\PushDeerDevice;
use App\Models\PushDeerKey;
use App\Models\PushDeerMessage;
use App\Models\PushDeerUser;
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

        $keys->each(function (PushDeerKey $key) use (&$messages, $data, &$result) {
            $message = new PushDeerMessage();
            $message->uid = $key->uid;
            $message->text = $data['text'] ?? '';
            $message->desp = $data['desp'] ?? '';
            $message->type = $data['type'] ?? 'markdown';
            $message->readkey = Str::random(32);
            $message->pushkey_name = $key->name;
            $message->save();

            $message->text = $message->type == 'image' ? '[图片]' : $message->text;
            if ($key->userDevices->count() < 1) {
                $this->message->addMessage(ReturnCode::ARGS, $key->name . ' 没有可用的设备，请先注册');
            }
            $key->userDevices->each(function (PushDeerDevice $device) use ($message, &$result) {
                $success = Event::fire((new DeviceNotificationProcessed($device, $message))->setTries(3));
                if ($success) {
                    $result[] = json_encode([
                        'counts'  => 1,
                        'logs'    => [],
                        'success' => 'ok',
                    ]);
                }
            });
        });
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