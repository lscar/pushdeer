<?php

namespace App\Http\Controllers;

use App\Http\ReturnCode;
use App\Services\AppleService;
use App\Services\PushDeerService;
use App\Services\PushDeerUserService;
use App\Services\WeChatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class PushDeerUserController extends Controller
{
    public function userInfo(Request $request)
    {
        $data = app(PushDeerUserService::class)->userInfo();

        return Response::success($data);
    }

    public function loginByFake(Request $request)
    {
        if (!app()->isLocal()) {
            return Response::error('Debug only', ReturnCode::ARGS);
        }

        $info = [
            'uid'   => 'theid999',
            'email' => 'easychen+new@gmail.com',
        ];

        $token = app(PushDeerUserService::class)->loginByApple($info['uid'], $info['email']);

        return Response::success(['token' => $token]);
    }

    public function loginBySimpleToken(Request $request)
    {
        $validated = $request->validate([
            'stoken' => ['required', 'string'],
        ]);

        $token = app(PushDeerUserService::class)->loginBySimpleToken($validated['stoken']);

        return Response::success(['token' => $token]);
    }

    public function simpleTokenRefresh(Request $request)
    {
        $stoken = app(PushDeerUserService::class)->simpleTokenRefresh();

        return Response::success(['stoken' => $stoken]);
    }

    public function simpleTokenRemove(Request $request)
    {
        $stoken = app(PushDeerUserService::class)->simpleTokenRefresh(true);

        return Response::success(['stoken' => $stoken]);
    }

    public function codeToUnionId(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required', 'string'],
        ]);

        $unionId = app(WeChatService::class)->getUserUnionId($validated['code']);

        return Response::success(['unionid' => $unionId]);
    }

    public function loginByWeChat(Request $request)
    {
        $validated = $request->validate([
            'code'        => ['required', 'string'],
            'self_hosted' => ['integer', 'nullable'],
        ]);

        if ($validated['self_hosted'] ?? false) {
            $unionId = app(PushDeerService::class)->getUserUnionId($validated['code']);
        } else {
            $unionId = app(WeChatService::class)->getUserUnionId($validated['code']);
        }

        $token = app(PushDeerUserService::class)->loginByWechat($unionId);

        return Response::success(['token' => $token]);
    }

    public function loginByApple(Request $request)
    {
        $validated = $request->validate([
            'idToken' => ['required', 'string'],
        ]);

        $info = app(AppleService::class)->getUserInfo($validated['idToken']);

        $token = app(PushDeerUserService::class)->loginByApple($info['uid'], $info['email']);

        return Response::success(['token' => $token]);
    }

    public function userMerge(Request $request)
    {
        $validated = $request->validate([
            'tokenorcode' => ['required', 'string'],
            'type'        => ['required', 'string'], // Apple or WeChat
        ]);

        if (strtolower($validated['type']) == 'apple') {
            $info = app(AppleService::class)->getUserInfo($validated['tokenorcode']);
            $code = $info['uid'];
        } else {
            $code = app(WeChatService::class)->getUserUnionId($validated['tokenorcode']);
        }

        app(PushDeerUserService::class)->userMerge($code);

        return Response::success(['result' => 'done']);
    }
}
