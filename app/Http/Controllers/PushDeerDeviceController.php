<?php

namespace App\Http\Controllers;

use App\Services\PushDeerDeviceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class PushDeerDeviceController extends Controller
{
    public function list()
    {
        $devices = app(PushDeerDeviceService::class)->list();

        return Response::success(['devices' => $devices]);
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'      => ['string', 'required'],
            'device_id' => ['string', 'required'],
            'is_clip'   => ['integer', 'nullable'],
            'type'      => ['string', 'min:1', 'nullable'],
        ]);

        app(PushDeerDeviceService::class)->save($validated);

        return $this->list();
    }

    public function rename(Request $request)
    {
        $validated = $request->validate([
            'id'   => ['integer', 'required'],
            'name' => ['string', 'required'],
        ]);

        $ret = app(PushDeerDeviceService::class)->rename($validated);
        if ($ret) {
            return Response::success(['message' => 'done']);
        }

        return Response::error('设备重命名失败，请稍后重试');
    }

    public function remove(Request $request)
    {
        $validated = $request->validate([
            'id' => ['integer', 'required'],
        ]);

        $ret = app(PushDeerDeviceService::class)->remove($validated);
        if ($ret) {
            return Response::success(['message' => 'done']);
        }

        return Response::error('设备删除失败，请稍后重试');
    }
}
