<?php

use App\Http\Controllers\PushDeerDeviceController;
use App\Http\Controllers\PushDeerKeyController;
use App\Http\Controllers\PushDeerMessageController;
use App\Http\Controllers\PushDeerUserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// 测试
Route::match(['get', 'post'], '/test', [\App\Http\Controllers\TestController::class, 'test']);

// 推送消息
Route::match(['get', 'post'], '/message/push', [PushDeerMessageController::class, 'push']);

Route::prefix('login')->group(function () {
    // 假登入，用于测试使用
    Route::match(['get', 'post'], '/fake', [PushDeerUserController::class, 'loginByFake']);
    // 通过 simple_token 登入
    Route::match(['get', 'post'], '/simple_token', [PushDeerUserController::class, 'loginBySimpleToken']);
    // 通过 apple 返回的 idtoken 登入
    Route::match(['get', 'post'],'/idtoken', [PushDeerUserController::class, 'loginByApple']);
    // 通过 微信客户端返回的 code 登入
    Route::match(['get', 'post'],'/wecode', [PushDeerUserController::class, 'loginByWeChat']);
    // 通过 微信客户端返回的 code 换取 UnionId 等信息
    Route::match(['get', 'post'], '/unoinid', [PushDeerUserController::class, 'codeToUnionId']);
});

Route::middleware('auth:api')->group(function () {
    Route::prefix('device')->group(function () {
        // 设备列表
        Route::post('/list', [PushDeerDeviceController::class, 'list']);
        // 注册设备
        Route::post('/reg', [PushDeerDeviceController::class, 'register']);
        // 重命名设备
        Route::post('/rename', [PushDeerDeviceController::class, 'rename']);
        // 删除设备
        Route::post('/remove', [PushDeerDeviceController::class, 'remove']);
    });

    Route::prefix('key')->group(function () {
        // key列表
        Route::post('/list', [PushDeerKeyController::class, 'list']);
        // 生成一个新key
        Route::post('/gen', [PushDeerKeyController::class, 'generate']);
        // 重置一个key
        Route::post('/regen', [PushDeerKeyController::class, 'regenerate']);
        // 重命名key
        Route::post('/rename', [PushDeerKeyController::class, 'rename']);
        // 删除一个key
        Route::post('/remove', [PushDeerKeyController::class, 'remove']);
    });

    Route::prefix('message')->group(function () {
        // 消息列表
        Route::post('/list', [PushDeerMessageController::class, 'list']);
        // 删除消息
        Route::post('/remove', [PushDeerMessageController::class, 'remove']);
        // 删除全部消息
        Route::post('/clean', [PushDeerMessageController::class, 'clean']);
    });

    Route::prefix('simple_token')->group(function () {
        // simpleToken 重新生成
        Route::post('/regen', [PushDeerUserController::class, 'simpleTokenRefresh']);
        // simpleToken 删除
        Route::post('/remove', [PushDeerUserController::class, 'simpleTokenRemove']);
    });

    Route::prefix('user')->group(function () {
        // 用户信息
        Route::post('/info', [PushDeerUserController::class, 'userInfo']);
        // 用户合并
        Route::post('/merge', [PushDeerUserController::class, 'userMerge']);
    });
});
