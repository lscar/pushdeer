<?php

namespace App\Admin\Render;

use App\Models\PushDeerDevice;
use App\Models\PushDeerUser;

class UserShowOnDevice extends UserShow
{
    protected function getUser(int $key = null): ?PushDeerUser
    {
        return PushDeerDevice::find($key)?->user;
    }
}