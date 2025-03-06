<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;

// Homepage
Route::get('/', [HomeController::class, 'index'])->name('home');


// Cart's index
Route::get('/cart', [CartController::class, 'cartIndex'])->name('cart.index');

// Cart's additional routes
Route::post('/cart/add', [CartController::class, 'addCart'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'updateCart'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'removeCart'])->name('cart.remove');

