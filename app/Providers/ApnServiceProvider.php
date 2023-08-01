<?php

namespace App\Providers;

use App\Services\Push\ApnAppAuthProvider;
use App\Services\Push\ApnAppClient;
use App\Services\Push\ApnClipAuthProvider;
use App\Services\Push\ApnClipClient;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Pushok\AuthProvider\Certificate;
use Pushok\Client;

class ApnServiceProvider extends ServiceProvider
{
    /**
     * The number of minutes to cache the client.
     *
     * @var int
     */
    const CACHE_MINUTES = 20;

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(ApnAppAuthProvider::class, function () {
            return Certificate::create(Arr::except(config('services.apn.app'), 'production')
            );
        });
        $this->app->singleton(ApnClipAuthProvider::class, function () {
            return Certificate::create(Arr::except(config('services.apn.clip'), 'production'));
        });

        $this->app->bind(ApnAppClient::class, function () {
            return cache()->remember(
                ApnAppClient::class,
                self::CACHE_MINUTES * 60,
                function () {
                    return new Client(
                        app()->make(ApnAppAuthProvider::class),
                        config('services.apn.app.production')
                    );
                });
        });

        $this->app->bind(ApnClipClient::class, function () {
            return cache()->remember(
                ApnClipClient::class,
                self::CACHE_MINUTES * 60,
                function () {
                    return new Client(
                        app()->make(ApnClipAuthProvider::class),
                        config('services.apn.clip.production')
                    );
                });
        });
    }
}
