<?php

namespace App\Console\Commands;

use App\Services\Push\PushService;
use App\Services\QueueService;
use PhpAmqpLib\Connection\AbstractConnection as AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;

trait SendNotificationTrait
{
    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $channel = app(AMQPConnection::class)->channel();
        $queueService = app(QueueService::class);
        $queueService->exchangeDeclareBind($channel);
        $queueService->queueDeclareBind($channel);
        $executionTime = time() + rand(60, 120);

        $channel->basic_qos(0, 100, false);
        $channel->basic_consume(
            queue: $this->queue->value,
            callback: function (AMQPMessage $message) {
                if (app(PushService::class)->sendBatch(unserialize($message->body))) {
                    $message->ack();
                }
            }
        );
        while ($channel->is_consuming()) {
            if (!$channel->wait(non_blocking: true)) {
                sleep(rand(1,5));
            }

            if (time() > $executionTime) {
                break;
            }
        }

        $channel->close();
    }
}
