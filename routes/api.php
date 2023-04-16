<?php

use App\Http\Controllers\AdminOrders;
use App\Http\Controllers\Controller;
use App\Http\Controllers\CouriersController;
use App\Http\Controllers\DispatcherController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\Settings;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\WorkersController;
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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();


Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('/order/getOrders', [\App\Http\Controllers\OrdersController::class, 'getOrders']);
    Route::post('/order/storeImage', [\App\Http\Controllers\OrdersController::class, 'storeImage']);
    Route::post('/order/removeImage', [\App\Http\Controllers\OrdersController::class, 'removeImage']);
    Route::post('/order/addNewComment', [\App\Http\Controllers\OrdersController::class, 'addNewComment']);
    Route::post('/order/updateComment', [\App\Http\Controllers\OrdersController::class, 'updateComment']);
    Route::post('/order/searchDrivers', [\App\Http\Controllers\OrdersController::class, 'searchDrivers']);
    Route::post('/order/searchSellers', [\App\Http\Controllers\OrdersController::class, 'searchSellers']);
    Route::post('/order/searchPoints', [\App\Http\Controllers\OrdersController::class, 'searchPoints']);
//    Route::post('/order/getTemporaryFiles', [\App\Http\Controllers\OrdersController::class, 'getTemporaryFiles']);
    Route::post('/order/addOrder', [\App\Http\Controllers\OrdersController::class, 'addOrder']);
    Route::post('/order/saveOrder', [\App\Http\Controllers\OrdersController::class, 'saveOrder']);
    Route::post('/order/getOrderOnce/{id}', [\App\Http\Controllers\OrdersController::class, 'getOrderOnce']);
    Route::post('/order/getOrdersSearchData', [\App\Http\Controllers\OrdersController::class, 'getOrdersSearchData']);

    Route::post('/worker/getWorkers', [\App\Http\Controllers\WorkersController::class, 'getWorkers']);
    Route::post('/worker/getWorkersSearchData', [\App\Http\Controllers\WorkersController::class, 'getWorkersSearchData']);
    Route::post('/worker/saveWorker', [\App\Http\Controllers\WorkersController::class, 'saveWorker']);
    Route::get('/worker/exportWorkers', [\App\Http\Controllers\WorkersController::class, 'exportWorkers']);

    Route::prefix('drivers')->group(function () {
        Route::post('/get', [\App\Http\Controllers\DriversController::class, 'getDrivers']);
        Route::post('/save', [\App\Http\Controllers\DriversController::class, 'saveDrivers']);
        Route::post('/delete', [\App\Http\Controllers\DriversController::class, 'destroy']);
        Route::get('/cities', [\App\Http\Controllers\CityController::class, 'getCities']);
    });
    Route::prefix('points')->group(function () {
        Route::post('/get', [\App\Http\Controllers\PointsController::class, 'get']);
        Route::post('/save', [\App\Http\Controllers\PointsController::class, 'save']);
        Route::post('/delete', [\App\Http\Controllers\PointsController::class, 'destroy']);
        Route::get('/raiony', [\App\Http\Controllers\PointsController::class, 'raiony']);
        Route::post('/saveNewRaion', [\App\Http\Controllers\PointsController::class, 'saveNewRaion']);
        Route::post('/saveNewCity', [\App\Http\Controllers\PointsController::class, 'saveNewCity']);
        Route::post('/addNewPoint', [\App\Http\Controllers\PointsController::class, 'addNewPoint']);
    });
    Route::prefix('admin_orders')->group(function () {
        Route::post('/', [AdminOrders::class, 'index']);
    });
    Route::prefix('settings')->group(function () {
        Route::get('/', [SettingsController::class, 'index']);
        Route::post('/save', [SettingsController::class, 'save']);
    });
    Route::prefix('couriers')->group(function () {
        Route::get('/', [CouriersController::class, 'index']);
        Route::post('/save', [CouriersController::class, 'save']);
    });

    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationsController::class, 'index']);
        Route::post('/save', [NotificationsController::class, 'save']);
        Route::get('/get_push_user', [NotificationsController::class, 'getUserWithPushToken']);
        Route::post('/send_push', [NotificationsController::class, 'sendPush']);
    });
    Route::post('/getUser', [\App\Http\Controllers\MainController::class, 'getUser']);
});
