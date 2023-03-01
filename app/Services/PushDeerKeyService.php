<?php

namespace App\Services;

use App\Http\ReturnCode;
use App\Models\PushDeerKey;
use App\Models\PushDeerUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PushDeerKeyService
{
    private ?PushDeerUser $user;

    public function __construct(public readonly MessageService $message)
    {
        $this->user = Auth::getUser();
    }

    public function list(): array
    {
        return PushDeerKey::whereUid($this->user?->id)
            ->select(['id', 'name', 'uid', 'key', 'created_at'])
            ->get()
            ->toArray();
    }

    public function generate(array $data = []): bool
    {
        if (empty($data['id'])) {
            $key = new PushDeerKey();
        } else {
            $key = PushDeerKey::whereUid($this->user?->id)
                ->where('id', '=', $data['id'])
                ->get()
                ->first();
            if (empty($key)) {
                $this->message->addMessage(ReturnCode::ARGS, 'Key不存在或已删除');
            }
        }
        $key->name = 'Key' . Str::random(8);
        $key->key = sprintf('PDU%dT%s', $this->user?->id, Str::random(32));

        return $this->user->userKeys()->save($key) != false;
    }

    public function rename(array $data): bool
    {
        /**
         * @var PushDeerKey $key
         */
        $key = PushDeerKey::whereUid($this->user?->id)
            ->where('id', '=', $data['id'])
            ->get()
            ->first();

        if (empty($key)) {
            $this->message->addMessage(ReturnCode::ARGS, 'Key不存在或已删除');
        }

        $key->name = $data['name'];

        return $key->save();
    }

    public function remove(array $data): bool
    {
        /**
         * @var PushDeerKey $key
         */
        $key = PushDeerKey::whereUid($this->user?->id)
            ->where('id', '=', $data['id'])
            ->get()
            ->first();

        if (empty($key)) {
            $this->message->addMessage(ReturnCode::ARGS, 'Key不存在或已删除');
        }

        return $key->delete();
    }
}