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
            return Certificate::create(Arr::except(config('services.apn'), 'production')
            );
        });
        $this->app->singleton(ApnClipAuthProvider::class, function () {
            return Certificate::create(Arr::except(config('services.apn_clip'), 'production'));
        });

        $this->app->bind(ApnAppClient::class, function () {
            return cache()->get(ApnAppClient::class);
        });
        $this->app->resolving(ApnAppClient::class, function () {
            cache()->remember(
                ApnAppClient::class,
                now()->addMinutes(static::CACHE_MINUTES)->timestamp,
                function () {
                    return new Client(
                        app()->make(ApnAppAuthProvider::class),
                        config('services.apn.production')
                    );
                });
        });

        $this->app->bind(ApnClipClient::class, function () {
            return cache()->get(ApnClipClient::class);
        });
        $this->app->resolving(ApnClipClient::class, function (): void {
            cache()->remember(
                ApnClipClient::class,
                now()->addMinutes(static::CACHE_MINUTES)->timestamp,
                function () {
                    return new Client(
                        app()->make(ApnClipAuthProvider::class),
                        config('services.apn_clip.production')
                    );
                });
        });
    }
}
