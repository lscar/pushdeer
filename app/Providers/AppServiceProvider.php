<?php

namespace App\Providers;

use App\Http\ReturnCode;
use App\Services\AppleService;
use App\Services\MessageService;
use App\Services\Push\ApnService;
use App\Services\Push\FcmService;
use App\Services\PushDeerDeviceService;
use App\Services\PushDeerKeyService;
use App\Services\PushDeerMessageService;
use App\Services\PushDeerService;
use App\Services\PushDeerUserService;
use App\Services\WeChatService;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        if ($this->app->isLocal()) {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
            $this->app->register(\KitLoong\MigrationsGenerator\MigrationsGeneratorServiceProvider::class);
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        Response::macro('success', function (array $data = []) {
            return Response::json([
                'code'    => (int)ReturnCode::SUCCESS->value,
                'content' => $data,
            ]);
        });

        Response::macro('error', function (string $message = '', ReturnCode $code = ReturnCode::DEFAULT) {
            return Response::json([
                'code'  => (int)$code->value,
                'error' => $message ?: trans('business.' . $code->value),
            ]);
        });

        $this->app->scoped(PushDeerDeviceService::class, function ($app) {
            return new PushDeerDeviceService($app->make(MessageService::class));
        });
        $this->app->scoped(PushDeerKeyService::class, function ($app) {
            return new PushDeerKeyService($app->make(MessageService::class));
        });
        $this->app->scoped(PushDeerMessageService::class, function ($app) {
            return new PushDeerMessageService($app->make(MessageService::class));
        });
        $this->app->scoped(PushDeerUserService::class, function ($app) {
            return new PushDeerUserService($app->make(MessageService::class));
        });
        $this->app->bind(AppleService::class, function ($app) {
            return new AppleService($app->make(MessageService::class));
        });
        $this->app->bind(WeChatService::class, function ($app) {
            return new WeChatService($app->make(MessageService::class));
        });
        $this->app->bind(PushDeerService::class, function ($app) {
            return new PushDeerService($app->make(MessageService::class));
        });
        $this->app->singleton(ApnService::class, function ($app) {
            return new ApnService();
        });
        $this->app->singleton(FcmService::class, function ($app) {
            return new FcmService();
        });
    }
}
