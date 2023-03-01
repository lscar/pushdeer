<?php

declare(strict_types=1);

use App\Http\ReturnCode;

return [
    ReturnCode::SUCCESS->value => '成功',
    ReturnCode::AUTH->value    => '权限认证错误',
    ReturnCode::ARGS->value    => '请求参数错误',
    ReturnCode::REMOTE->value  => '远程服务错误',
    ReturnCode::DEFAULT->value => '未知错误，请联系管理员',
];