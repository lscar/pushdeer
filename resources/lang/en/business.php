<?php

declare(strict_types=1);

use App\Http\ReturnCode;

return [
    ReturnCode::SUCCESS->value => 'Success',
    ReturnCode::AUTH->value    => 'Permission authentication error',
    ReturnCode::ARGS->value    => 'Request parameter error',
    ReturnCode::REMOTE->value  => 'Remote service error',
    ReturnCode::DEFAULT->value => 'Unknown error, please contact administrator',
];