<?php

namespace App\Providers;

use App\Services\MessageService;
use App\Services\QueueService;
use Illuminate\Support\ServiceProvider;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AbstractConnection as AMQPConnection;
use PhpAmqpLib\Connection\AMQPConnectionConfig;
use PhpAmqpLib\Connection\AMQPConnectionFactory;
use PhpAmqpLib\Connection\AMQPSocketConnection;
use PhpAmqpLib\Wire\AMQPTable;

class RabbitMQProvider extends ServiceProvider
{
    protected AMQPChannel $channel;

    public function register(): void
    {
        $this->app->singleton(AMQPConnectionConfig::class, function () {
            $rabbitmq = config('services.rabbitmq');

            $config = new AMQPConnectionConfig();
            $config->setHost($rabbitmq['host']);
            $config->setPort($rabbitmq['port']);
            $config->setUser($rabbitmq['user']);
            $config->setPassword($rabbitmq['password']);
            $config->setVhost($rabbitmq['vhost']);
            $config->setIsLazy(true);

            return $config;
        });

        $this->app->singleton(AMQPSocketConnection::class, function () {
            return $this->app
                ->make(AMQPConnectionFactory::class)
                ->create($this->app->make(AMQPConnectionConfig::class));
        });

        $this->app->singleton(AMQPConnection::class, function () {
            $connection = $this->app->make(AMQPSocketConnection::class);

            $channel = $connection->channel();
            $queueService = $this->app->make(QueueService::class);
            $queueService->exchangeDeclareBind($channel);
            $queueService->queueDeclareBind($channel);
            $channel->close();

            return $connection;
        });

        $this->app->singleton(QueueService::class, function () {
            return new QueueService();
        });
    }
}