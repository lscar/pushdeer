<?php

namespace App\Services;

use App\Http\ReturnCode;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Facades\Log;

class WeChatService
{
    public function __construct(public readonly MessageService $message)
    {

    }

    public function getUserUnionId(string $code): string
    {
        if ($this->message->messages()->isNotEmpty()) {
            $this->message->cleanMessage();
        }

        $client = new Client(['base_uri' => 'https://api.weixin.qq.com']);

        $params = [
            'appid'      => config('services.wechat.app_id'),
            'secret'     => config('services.wechat.app_secret'),
            'code'       => $code,
            'grant_type' => 'authorization_code',
        ];
        try {
            $response = $client->get('/sns/oauth2/access_token', [
                RequestOptions::QUERY           => $params,
                RequestOptions::TIMEOUT         => 5,
                RequestOptions::CONNECT_TIMEOUT => 5,
            ]);
        } catch (GuzzleException $e) {
            Log::error('WeChatRequest', $params);
            $this->message->addMessage(ReturnCode::REMOTE, '网络异常');
            return '';
        }

        $result = json_decode($response->getBody()->getContents(), true);
        if (!isset($result['access_token']) || !isset($result['unionid'])) {
            $this->message->addMessage(ReturnCode::REMOTE, '错误的Code');
        }

        return (string)$result['unionid'];
    }
}