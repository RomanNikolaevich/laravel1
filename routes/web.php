<?php

use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MainController::class, 'index']);
Route::get('/categories', [MainController::class, 'categories']);
Route::get('/{category:code}', [MainController::class, 'category']);
Route::get('/mobiles/{product?}', [MainController::class, 'product']);//? означает, что этот параметр не обязательный
