<?php

namespace App\Http\Controllers;

use App\Services\PushDeerKeyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class PushDeerKeyController extends Controller
{
    public function list()
    {
        $keys = app(PushDeerKeyService::class)->list();

        return Response::success(['keys' => $keys]);
    }

    public function generate(Request $request)
    {
        app(PushDeerKeyService::class)->generate();

        return $this->list();
    }

    public function regenerate(Request $request)
    {
        $validated = $request->validate([
            'id' => ['integer', 'required'],
        ]);

        $ret = app(PushDeerKeyService::class)->generate($validated);
        if ($ret) {
            return Response::success(['message' => 'done']);
        }

        return Response::error('Key重生成失败，请稍后重试');
    }

    public function rename(Request $request)
    {
        $validated = $request->validate([
            'id'   => ['integer', 'required'],
            'name' => ['string', 'required'],
        ]);

        $ret = app(PushDeerKeyService::class)->rename($validated);
        if ($ret) {
            return Response::success(['message' => 'done']);
        }

        return Response::error('Key重命名失败，请稍后重试');
    }

    public function remove(Request $request)
    {
        $validated = $request->validate([
            'id' => ['integer', 'required'],
        ]);

        $ret = app(PushDeerKeyService::class)->remove($validated);
        if ($ret) {
            return Response::success(['message' => 'done']);
        }

        return Response::error('Key删除失败，请稍后重试');
    }
}
