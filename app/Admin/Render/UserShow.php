<?php

namespace App\Admin\Render;

use App\Models\PushDeerDevice;
use App\Models\PushDeerUser;
use Encore\Admin\Widgets\Table;

abstract class UserShow extends Table
{
    abstract protected function getUser(int $key = null): ?PushDeerUser;

    public function render(int $key = null): string
    {
        $user = $this->getUser($key);

        $headers = [
            __('pushdeer.render.item'),
            __('pushdeer.render.value'),
        ];
        $this->setHeaders($headers);

        $rows = [
            __('pushdeer.user.id')           => $user->id ?? '',
            __('pushdeer.user.name')         => $user->name ?? '',
            __('pushdeer.user.email')        => $user->email ?? '',
            __('pushdeer.user.apple_id')     => $user->apple_id ?? '',
            __('pushdeer.user.wechat_id')    => $user->wechat_id ?? '',
            __('pushdeer.user.simple_token') => $user->simple_token ?? '',
            __('pushdeer.user.level')        => $user->level ?? '',
            __('pushdeer.user.created_at')   => $user->created_at ?? '',
            __('pushdeer.user.updated_at')   => $user->updated_at ?? '',
        ];
        $this->setRows($rows);

        $vars = [
            'headers'    => $this->headers,
            'rows'       => $this->rows,
            'style'      => $this->style,
            'attributes' => $this->formatAttributes(),
        ];

        return view($this->view, $vars)->render();
    }
}