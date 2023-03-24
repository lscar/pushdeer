<?php

namespace App\Services\Push;

use Pushok\Client;

class ApnClipService extends ApnServiceAbstract
{

    protected function getClient(): Client
    {
        return app(ApnClipClient::class);
    }
}