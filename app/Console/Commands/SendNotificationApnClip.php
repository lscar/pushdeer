<?php

namespace App\Console\Commands;

use App\Models\QueueExchangeEnum;
use Illuminate\Console\Command;

class SendNotificationApnClip extends Command
{
    use SendNotificationTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-notification-apn-clip';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '处理队列消息推送APN-clip内容';

    protected QueueExchangeEnum $queue = QueueExchangeEnum::NOTIFICATION_APN_CLIP_QUEUE;
}