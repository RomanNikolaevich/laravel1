<?php

use App\Http\Controllers\Api\Admin\CategoryController;
use App\Http\Controllers\Api\Admin\OrderController;
use App\Http\Controllers\Api\Admin\ProductController;
use App\Services\Admin\CurrencyService;
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

Route::name('api.')
    ->group(static function () {
        Route::apiResource('products', ProductController::class);
        Route::apiResource('categories', CategoryController::class);
        Route::apiResource('orders', OrderController::class);
        Route::get('currencies/record-rate', static function () {
            $service = new CurrencyService();
            $service->getNewCurrencies();
        });
        Route::post('currencies/read-rate', static function () {
            $service = new CurrencyService();
            $service->getCurrency('date', 'code');
        });
    });




