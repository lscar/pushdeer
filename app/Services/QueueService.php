<?php

namespace App\Services;

use App\Models\QueueExchangeEnum;
use App\Models\RoutingKeyEnum;
use Illuminate\Support\Arr;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AbstractConnection as AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;

class QueueService
{

    /**
     * @param mixed $data
     * @param QueueExchangeEnum $exchange
     * @param RoutingKeyEnum $routingKey
     * @param bool $multi
     * @return void
     */
    public function publish(
        mixed             $data,
        QueueExchangeEnum $exchange,
        RoutingKeyEnum    $routingKey = RoutingKeyEnum::DEFAULT,
        bool              $multi = false
    ): void
    {
        $channel = app(AMQPConnection::class)->channel();

        if (is_array($data) && $multi) {
            foreach ($data as $item) {
                $channel->batch_basic_publish(new AMQPMessage(serialize($item)), $exchange->value, $routingKey->value);
            }
            $channel->publish_batch();
        } else {
            $channel->basic_publish(new AMQPMessage(serialize($data)), $exchange->value, $routingKey->value);
        }

        $channel->close();
    }

    public function queueDeclareBind(AMQPChannel $channel): void
    {
        $queues = config('services.rabbitmq.queues');
        foreach ($queues['declare'] as $declare) {
            $declare = Arr::only($declare, ['queue', 'passive', 'durable', 'exclusive', 'auto_delete', 'nowait', 'arguments', 'ticket']);
            $declare['arguments'] = new AMQPTable($declare['arguments'] ?? []);
            call_user_func_array([$channel, 'queue_declare'], $declare);
        }
        foreach ($queues['bind'] as $bind) {
            $bind = Arr::only($bind, ['queue', 'exchange', 'routing_key', 'nowait', 'arguments', 'ticket']);
            $bind['arguments'] = new AMQPTable($bind['arguments'] ?? []);
            call_user_func_array([$channel, 'queue_bind'], $bind);
        }
    }

    public function exchangeDeclareBind(AMQPChannel $channel): void
    {
        $exchanges = config('services.rabbitmq.exchanges');
        foreach ($exchanges['declare'] as $declare) {
            $declare = Arr::only($declare, ['exchange', 'type', 'passive', 'durable', 'auto_delete', 'internal', 'nowait', 'arguments', 'ticket']);
            $declare['arguments'] = new AMQPTable($declare['arguments'] ?? []);
            call_user_func_array([$channel, 'exchange_declare'], $declare);
        }
        foreach ($exchanges['bind'] as $bind) {
            $bind = Arr::only($bind, ['destination', 'source', 'routing_key', 'nowait', 'arguments', 'ticket']);
            $bind['arguments'] = new AMQPTable($bind['arguments'] ?? []);
            call_user_func_array([$channel, 'exchange_bind'], $bind);
        }
    }
}