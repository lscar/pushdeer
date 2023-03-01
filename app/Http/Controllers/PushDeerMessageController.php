<?php

namespace App\Http\Controllers;

use App\Http\ReturnCode;
use App\Services\PushDeerMessageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class PushDeerMessageController extends Controller
{
    public function list(Request $request)
    {
        $validated = $request->validate([
            'limit'    => ['integer', 'nullable', 'min:0'],
            'since_id' => ['integer', 'nullable', 'min:0'],
        ]);

        $messages = app(PushDeerMessageService::class)->list($validated);

        return Response::success(['messages' => $messages]);
    }

    public function push(Request $request)
    {
        $validated = $request->validate([
            'pushkey' => ['string', 'required'],
            'text'    => ['string', 'required'],
            'desp'    => ['string', 'nullable'],
            'type'    => ['string', 'nullable'],
        ]);

        $service = app(PushDeerMessageService::class);
        $service->push($validated);
        $ret = $service->message->getData();

        return Response::success(['result' => $ret]);
    }


    public function remove(Request $request)
    {
        $validated = $request->validate([
            'id' => ['integer', 'required', 'min:0'],
        ]);

        $ret = app(PushDeerMessageService::class)->remove($validated);
        if ($ret) {
            return Response::success(['message' => 'done']);
        }

        return Response::error('消息不存在或已删除', ReturnCode::ARGS);
    }

    public function clean(Request $request)
    {
        $ret = app(PushDeerMessageService::class)->remove();
        if ($ret) {
            return Response::success(['message' => 'done']);
        }

        return Response::error('消息不存在或已删除', ReturnCode::ARGS);
    }
}
