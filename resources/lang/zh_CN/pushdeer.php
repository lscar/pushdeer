<?php

declare(strict_types=1);

return [
    'title'   => [
        'device'  => '用户设备',
        'key'     => '用户秘钥',
        'message' => '用户消息',
        'user'    => '用户信息',
    ],
    'render'  => [
        'item'  => '项',
        'value' => '值',
    ],
    'device'  => [
        'id'         => 'ID',
        'name'       => '设备名称',
        'type'       => '设备类型',
        'device_id'  => '设备ID',
        'uid'        => '用户表ID',
        'is_clip'    => '是否快应用',
        'created_at' => '添加时间',
        'updated_at' => '更新时间',
    ],
    'key'     => [
        'id'         => 'ID',
        'uid'        => '用户表ID',
        'key'        => '秘钥',
        'name'       => '秘钥名称',
        'created_at' => '生成时间',
        'updated_at' => '更新时间',
    ],
    'message' => [
        'id'           => 'ID',
        'readkey'      => '阅读秘钥',
        'pushkey_name' => '秘钥名称',
        'text'         => '消息内容',
        'desp'         => '消息描述',
        'uid'          => '用户表ID',
        'type'         => '消息类型',
        'url'          => '消息链接',
        'created_at'   => '创建时间',
        'updated_at'   => '更新时间',
    ],
    'user'    => [
        'id'           => 'ID',
        'name'         => '用户名称',
        'email'        => '邮箱',
        'apple_id'     => '苹果ID',
        'wechat_id'    => '微信ID',
        'simple_token' => 's-token',
        'level'        => '状态',
        'created_at'   => '注册时间',
        'updated_at'   => '更新时间',
    ],
];