<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');

    $router->resource('push-deer-devices', \App\Admin\Controllers\PushDeerDeviceController::class);
    $router->resource('push-deer-keys', \App\Admin\Controllers\PushDeerKeyController::class);
    $router->resource('push-deer-messages', \App\Admin\Controllers\PushDeerMessageController::class);
    $router->resource('push-deer-users', \App\Admin\Controllers\PushDeerUserController::class);
});
