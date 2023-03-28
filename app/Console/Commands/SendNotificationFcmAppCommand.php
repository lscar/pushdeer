<?php

namespace App\Console\Commands;

use App\Models\QueueExchangeEnum;
use Illuminate\Console\Command;

class SendNotificationFcmAppCommand extends Command
{
    use SendNotificationCommandTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-notification-fcm-app';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '处理队列消息推送FCM-app内容';

    protected QueueExchangeEnum $queue = QueueExchangeEnum::NOTIFICATION_FCM_APP_QUEUE;
}