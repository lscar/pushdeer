<?php

namespace App\Services;

use App\Http\ReturnCode;
use App\Models\PushDeerDevice;
use App\Models\PushDeerUser;
use Illuminate\Support\Facades\Auth;

class PushDeerDeviceService
{
    private ?PushDeerUser $user;

    public function __construct(public readonly MessageService $message)
    {
        $this->user = Auth::getUser();
    }

    public function list(): array
    {

        return PushDeerDevice::whereUid($this->user?->id)
            ->select(['id', 'uid', 'name', 'type', 'device_id', 'is_clip'])
            ->get()
            ->toArray();
    }

    public function save(array $data): bool
    {
        $device = PushDeerDevice::whereUid($this->user?->id)
            ->where('device_id', '=', $data['device_id'])
            ->first();

        $device = $device ?? new PushDeerDevice();
        $device->uid = $this->user?->id;
        $device->name = $data['name'] ?? '';
        $device->type = trim($data['type'] ?? 'ios');
        $device->is_clip = intval($data['is_clip']);
        $device->device_id = $data['device_id'] ?? '';

        return $device->save();
    }

    public function rename(array $data): bool
    {
        /**
         * @var PushDeerDevice $device
         */
        $device = PushDeerDevice::whereUid($this->user?->id)
            ->where('device_id', '=', $data['id'])
            ->get()
            ->first();

        if (empty($device)) {
            $this->message->addMessage(ReturnCode::ARGS, '设备不存在或已注销');
        }

        $device->name = $data['name'];

        return $device->save();
    }

    public function remove(array $data): bool
    {
        $device = PushDeerDevice::whereUid($this->user?->id)
            ->where('device_id', '=', $data['id'])
            ->first();

        if (empty($device)) {
            $this->message->addMessage(ReturnCode::ARGS, '设备不存在或已注销');
        }

        return $device->delete();
    }
}