<?php

namespace App\Console\Commands;

use App\Models\QueueExchangeEnum;
use Illuminate\Console\Command;

class SendNotificationApnApp extends Command
{
    use SendNotificationTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-notification-apn-app';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '处理队列消息推送APN-app内容';

    protected QueueExchangeEnum $queue = QueueExchangeEnum::NOTIFICATION_APN_APP_QUEUE;
}