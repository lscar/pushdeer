<?php

use App\Models\QueueExchangeEnum;
use App\Models\RoutingKeyEnum;

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain'   => env('MAILGUN_DOMAIN'),
        'secret'   => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key'    => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'limit' => [
        'api'     => env('MAX_PUSH_EVERY_USER_PER_MINUTE', 60),
        'message' => [
            'cache' => env('MAX_PUSH_EXISTS_DAYS', 0),
            'push'  => env('MAX_PUSH_KEY_PER_TIME', 100),
        ],
    ],

    'rabbitmq' => [
        'driver' => 'rabbitmq',
        'host' => env('RABBITMQ_HOST', '127.0.0.1'),
        'port' => env('RABBITMQ_PORT', 5672),
        'user' => env('RABBITMQ_USER', 'guest'),
        'password' => env('RABBITMQ_PASSWORD', 'guest'),
        'vhost' => env('RABBITMQ_VHOST', '/'),
        'exchanges' => [
            'declare' => [
                [
                    'exchange' => QueueExchangeEnum::NOTIFICATION_EXCHANGE->value,
                    'type' => 'direct',
                    'durable' => true,
                    'auto_delete' => false,
                ],
            ],
            'bind' => [],
        ],
        'queues' => [
            'declare' => [
                [
                    'queue' => QueueExchangeEnum::NOTIFICATION_APN_APP_QUEUE->value,
                    'durable' => true,
                    'auto_delete' => false,
                ],
                [
                    'queue' => QueueExchangeEnum::NOTIFICATION_APN_CLIP_QUEUE->value,
                    'durable' => true,
                    'auto_delete' => false,
                ],
                [
                    'queue' => QueueExchangeEnum::NOTIFICATION_FCM_APP_QUEUE->value,
                    'durable' => true,
                    'auto_delete' => false,
                ],
                [
                    'queue' => QueueExchangeEnum::NOTIFICATION_FCM_CLIP_QUEUE->value,
                    'durable' => true,
                    'auto_delete' => false,
                ],
            ],
            'bind' => [
                [
                    'queue' => QueueExchangeEnum::NOTIFICATION_APN_APP_QUEUE->value,
                    'exchange' => QueueExchangeEnum::NOTIFICATION_EXCHANGE->value,
                    'routing_key' => RoutingKeyEnum::NOTIFICATION_APN_APP->value,
                ],
                [
                    'queue' => QueueExchangeEnum::NOTIFICATION_APN_CLIP_QUEUE->value,
                    'exchange' => QueueExchangeEnum::NOTIFICATION_EXCHANGE->value,
                    'routing_key' => RoutingKeyEnum::NOTIFICATION_APN_CLIP->value,
                ],
                [
                    'queue' => QueueExchangeEnum::NOTIFICATION_FCM_APP_QUEUE->value,
                    'exchange' => QueueExchangeEnum::NOTIFICATION_EXCHANGE->value,
                    'routing_key' => RoutingKeyEnum::NOTIFICATION_FCM_APP->value,
                ],
                [
                    'queue' => QueueExchangeEnum::NOTIFICATION_FCM_CLIP_QUEUE->value,
                    'exchange' => QueueExchangeEnum::NOTIFICATION_EXCHANGE->value,
                    'routing_key' => RoutingKeyEnum::NOTIFICATION_FCM_CLIP->value,
                ],
            ],
        ],
    ],

    'apn' => [
        'app' => [
            'app_bundle_id' => env('APN_BUNDLE_ID_APP'),
            'certificate_path' => env('APN_CERTIFICATE_PATH_APP'),
            'certificate_secret' => env('APN_CERTIFICATE_SECRET'),
            'production' => env('APN_PRODUCTION', true),
        ],
        'clip' => [
            'app_bundle_id' => env('APN_BUNDLE_ID_CLIP'),
            'certificate_path' => env('APN_CERTIFICATE_PATH_CLIP'),
            'certificate_secret' => env('APN_CERTIFICATE_SECRET'),
            'production' => env('APN_PRODUCTION', true),
        ],
    ],

    'fcm' => [
        'app' => [],
        'clip' => [],
    ],

    'wechat' => [
        'app_id'     => env('WECHAT_APPID', ''),
        'app_secret' => env('WECHAT_APPSECRET', ''),
    ],

    'android' => [
        'package'        => env('ANDROID_PACKAGE', 'com.pushdeer.app.os'),
        'mi_push_secret' => env('MIPUSH_SECRET', ''),
    ],
];
