<?php

namespace App\Models;

enum QueueExchangeEnum: string
{
    case NOTIFICATION_EXCHANGE       = 'notification_exchange';
    case NOTIFICATION_APN_APP_QUEUE  = 'notification_apn_app_queue';
    case NOTIFICATION_APN_CLIP_QUEUE = 'notification_apn_clip_queue';
    case NOTIFICATION_FCM_APP_QUEUE  = 'notification_fcm_app_queue';
    case NOTIFICATION_FCM_CLIP_QUEUE = 'notification_fcm_clip_queue';
}
