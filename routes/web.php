<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BasketController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\ResetController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes([
    'reset'   => false,
    'confirm' => false,
    'verify'  => false,
]);

Route::get('locale/{locale}', [MainController::class, 'changeLocale'])->name('locale');

Route::get('reset', [ResetController::class, 'reset'])->name('reset');

Route::get('/logout', [LoginController::class, 'logout'])->name('get-logout');

Route::middleware(['set_locale'])->group(function () {
    Route::middleware(['auth'])->group(function () {
        Route::group([
            'prefix'    => 'person',
            'namespace' => 'Person',
            'as'        => 'person.',
        ], static function () {
            Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
            Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        });

        Route::group([
            'prefix' => 'admin',
        ], static function () {
            Route::group(['middleware' => 'is_admin'], static function () {
                Route::get('/orders', [OrderController::class, 'index'])->name('home');
                Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
            });

            Route::resource('categories', CategoryController::class);
            Route::resource('products', ProductController::class);
        });
    });

    Route::get('/', [MainController::class, 'index'])->name('index');
    Route::get('/categories', [MainController::class, 'categories'])->name('categories');

    Route::group([
        'middleware' => 'basket_not_empty',
        'prefix'     => 'basket',
    ], static function () {
        Route::get('/', [BasketController::class, 'basket'])->name('basket');
        Route::get('/place', [BasketController::class, 'basketPlace'])->name('basket-place');

        Route::post('/remove/{id}', [BasketController::class, 'basketRemove'])->name('basket-remove');
        Route::post('/place', [BasketController::class, 'basketConfirm'])->name('basket-confirm');
    });

    Route::post('/basket/add/{id}', [BasketController::class, 'basketAdd'])->name('basket-add');

    Route::get('/{category}', [MainController::class, 'category'])->name('category');
    Route::get('/{category}/{product?}', [MainController::class, 'product'])
        ->name('product');
});
