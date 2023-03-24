<?php

namespace App\Models;

enum RoutingKeyEnum: string
{
    case DEFAULT               = '';
    case NOTIFICATION_APN_APP  = 'notification.apn.app';
    case NOTIFICATION_APN_CLIP = 'notification.apn.clip';
    case NOTIFICATION_FCM_APP  = 'notification.fcm.app';
    case NOTIFICATION_FCM_CLIP = 'notification.fcm.clip';
}
