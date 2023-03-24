<?php

namespace App\Admin\Render;

use App\Models\PushDeerMessage;
use App\Models\PushDeerUser;

class UserShowOnMessage extends UserShow
{
    protected function getUser(int $key = null): ?PushDeerUser
    {
        return PushDeerMessage::find($key)?->user;
    }
}