<?php

namespace App\Console\Commands;

use App\Models\QueueExchangeEnum;
use Illuminate\Console\Command;

class SendNotificationFcmClipCommand extends Command
{
    use SendNotificationCommandTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-notification-fcm-clip';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '处理队列消息推送FCM-clip内容';

    protected QueueExchangeEnum $queue = QueueExchangeEnum::NOTIFICATION_FCM_CLIP_QUEUE;
}