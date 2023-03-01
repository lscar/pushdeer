<?php

namespace App\Services;

use App\Http\ReturnCode;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Facades\Log;

class PushDeerService
{
    public function __construct(public readonly MessageService $message)
    {

    }

    public function getUserUnionId($code): string
    {
        if ($this->message->messages()->isNotEmpty()) {
            $this->message->cleanMessage();
        }

        $client = new Client(['base_uri' => 'https://api2.pushdeer.com']);

        $params = [
            'code'      => $code,
        ];
        try {
            $response = $client->get('/login/unoinid', [
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
        if (!isset($result['content']['unionid'])) {
            $this->message->addMessage(ReturnCode::REMOTE, '错误的Code');
        }

        return (string)$result['content']['unionid'];
    }
}