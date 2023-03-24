<?php

namespace App\Services;

use App\Http\ReturnCode;
use App\Models\PushDeerDevice;
use App\Models\PushDeerMessage;
use App\Models\PushDeerUser;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Str;
use Throwable;

class PushDeerUserService
{
    private ?PushDeerUser $user;

    public function __construct(public readonly MessageService $message)
    {
        $this->user = Auth::getUser();
    }

    public function userInfo(int $uid = null): array
    {
        if (empty($uid)) {
            $user = $this->user;
        } else {
            $user = PushDeerUser::whereId($uid)
                ->get()
                ->first();
        }

        return empty($user) ? [] : $user->toArray();
    }

    public function userMerge(string $code): void
    {
        /**
         * @var PushDeerUser $checkUser
         */
        $checkUser = PushDeerUser::where('apple_id', '=', $code)
            ->orWhere('wechat_id', '=', $code)
            ->get()
            ->first();
        $currentUser = $this->user;

        if ($checkUser->id == $currentUser->id) {
            $this->message->addMessage(ReturnCode::ARGS, '不能合并当前账号本身');
        }

        try {
            DB::transaction(function () use ($checkUser, $currentUser) {
                PushDeerDevice::whereUid($checkUser->id)
                    ->chunkById(10, function ($device) use ($currentUser) {
                        $device->each->update(['uid', $currentUser->id]);
                    });
                PushDeerMessage::whereUid($checkUser->id)
                    ->chunkById(50, function ($device) use ($currentUser) {
                        $device->each->update(['uid', $currentUser->id]);
                    });

                $checkUser->userKeys->delete();
                $checkUser->delete();

                $currentUser->apple_id = $currentUser->apple_id ?? $checkUser->apple_id;
                $currentUser->wechat_id = $currentUser->wechat_id ?? $checkUser->wechat_id;
                $currentUser->save();
            }, 3);
        } catch (Throwable $e) {
            Log::error('UserMerge', [
                'params'  => $code,
                'message' => $e->getMessage(),
            ]);
            $this->message->addMessage(ReturnCode::DEFAULT, '账号合并错误，请稍后重试');
        }
    }

    public function loginByApple(string $appleId, string $email): string
    {
        /**
         * @var PushDeerUser $pdUser
         */
        $pdUser = PushDeerUser::whereAppleId($appleId)
            ->get()
            ->first();

        if (empty($pdUser)) {
            $pdUser = new PushDeerUser();
            $pdUser->apple_id = $appleId;
            $pdUser->email = $email;
            $pdUser->name = Str::before($email, '@');
            $pdUser->level = 1;

            $pdUser->save();
        }

        if ($pdUser->level < 1) {
            $this->message->addMessage(ReturnCode::AUTH, '账号已被禁用');
        }

        return Auth::setTTL(86400 * 365)->attempt([
            'email'    => $pdUser->email,
            'password' => $pdUser->email,
        ]);
    }

    public function loginByWechat(string $unionid): string
    {
        /**
         * @var PushDeerUser $pdUser
         */
        $pdUser = PushDeerUser::whereWechatId($unionid)
            ->get()
            ->first();

        if (empty($pdUser)) {
            $pdUser = new PushDeerUser();
            $pdUser->wechat_id = $unionid;
            $pdUser->email = $unionid . '@fake.pushdeer.com';
            $pdUser->name = '微信用户' . substr($unionid, 0, 6);
            $pdUser->level = 1;

            $pdUser->save();
        }

        if ($pdUser->level < 1) {
            $this->message->addMessage(ReturnCode::AUTH, '账号已被禁用');
        }

        return Auth::setTTL(86400 * 30)->attempt([
            'email'    => $pdUser->email,
            'password' => $pdUser->email,
        ]);
    }

    public function loginBySimpleToken(string $simpleToken): string
    {
        /**
         * @var PushDeerUser $pdUser
         */
        $pdUser = PushDeerUser::whereSimpleToken($simpleToken)
            ->get()
            ->first();

        if (empty($pdUser)) {
            $this->message->addMessage(ReturnCode::AUTH, 'token无效');
        }
        if ($pdUser->level < 1) {
            $this->message->addMessage(ReturnCode::AUTH, '账号已被禁用');
        }

        return Auth::setTTL(86400 * 30)->attempt([
            'email'    => $pdUser->email,
            'password' => $pdUser->email,
        ]);
    }

    public function simpleTokenRefresh(bool $isClear = false): string
    {
        $user = $this->user;

        $user->simple_token = $isClear ? '' : sprintf('SP%dP%s', $user->id, md5(uniqid(rand(), true)));
        $user->save();

        return $user->simple_token;
    }
}