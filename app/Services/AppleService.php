<?php

namespace App\Services;

use App\Http\ReturnCode;
use AppleSignIn\ASDecoder;
use JetBrains\PhpStorm\ArrayShape;

class AppleService
{
    public function __construct(public readonly MessageService $message)
    {

    }

    #[ArrayShape([
        'email' => 'string',
        'uid'   => 'string',
    ])]
    public function getUserInfo($code): array
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