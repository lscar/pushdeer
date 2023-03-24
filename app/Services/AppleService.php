<?php

namespace App\Services;

use App\Http\ReturnCode;
use AppleSignIn\ASDecoder;

class AppleService
{
    public function __construct(public readonly MessageService $message)
    {

    }

    /**
     * @param string $code
     * @return array{email: string, uid: string}
     */
    public function getUserInfo(string $code): array
    {
        if ($this->message->messages()->isNotEmpty()) {
            $this->message->cleanMessage();
        }

        $appleSignInPayload = ASDecoder::getAppleSignInPayload($code);

        if (empty($appleSignInPayload->getUser())) {
            $this->message->addMessage(ReturnCode::ARGS, 'id_token解析错误');
        }

        return [
            'email' => $appleSignInPayload->getEmail(),
            'uid'   => $appleSignInPayload->getUser(),
        ];
    }
}