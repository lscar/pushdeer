<?php

namespace App\Providers;

use App\Console\Commands\SendNotificationApnAppCommand;
use App\Console\Commands\SendNotificationApnClipCommand;
use App\Console\Commands\SendNotificationFcmAppCommand;
use App\Console\Commands\SendNotificationFcmClipCommand;
use App\Http\ReturnCode;
use App\Services\AppleService;
use App\Services\MessageService;
use App\Services\Push\ApnAppService;
use App\Services\Push\ApnClipService;
use App\Services\Push\FcmAppService;
use App\Services\Push\FcmClipService;
use App\Services\Push\PushServiceInterface;
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

        // push-apn-app
        $this->app->singleton(ApnAppService::class, function () {
            return new ApnAppService();
        });
        $this->app->when(SendNotificationApnAppCommand::class)
            ->needs(PushServiceInterface::class)
            ->give(ApnAppService::class);

        // push-apn-clip
        $this->app->singleton(ApnClipService::class, function () {
            return new ApnClipService();
        });
        $this->app->when(SendNotificationApnClipCommand::class)
            ->needs(PushServiceInterface::class)
            ->give(ApnClipService::class);

        // push-fcm-app
        $this->app->singleton(FcmAppService::class, function () {
            return new FcmAppService();
        });
        $this->app->when(SendNotificationFcmAppCommand::class)
            ->needs(PushServiceInterface::class)
            ->give(FcmAppService::class);

        // push-fcm-clip
        $this->app->singleton(FcmClipService::class, function () {
            return new FcmClipService();
        });
        $this->app->when(SendNotificationFcmClipCommand::class)
            ->needs(PushServiceInterface::class)
            ->give(FcmClipService::class);

    }
}
