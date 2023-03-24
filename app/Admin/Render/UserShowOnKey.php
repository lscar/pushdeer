<?php

namespace App\Admin\Render;

use App\Models\PushDeerKey;
use App\Models\PushDeerUser;

class UserShowOnKey extends UserShow
{
    protected function getUser(int $key = null): ?PushDeerUser
    {
        return PushDeerKey::find($key)?->user;
    }
}