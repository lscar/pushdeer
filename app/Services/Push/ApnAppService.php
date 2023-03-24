<?php

namespace App\Services\Push;

use Pushok\Client;

class ApnAppService extends ApnServiceAbstract
{

    protected function getClient(): Client
    {
        return app(ApnAppClient::class);
    }
}