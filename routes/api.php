<?php

use App\Http\Controllers\Api\Admin\CategoryController;
use App\Http\Controllers\Api\Admin\CurrencyController;
use App\Http\Controllers\Api\Admin\OrderController;
use App\Http\Controllers\Api\Admin\ProductController;
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

		Route::get('currencies/update-rates', [CurrencyController::class, 'updateRates'])->name('currencies.update_rates');
		Route::get('currencies/read-rate', [CurrencyController::class, 'readRate'])->name('currencies.read_rate');
	});
