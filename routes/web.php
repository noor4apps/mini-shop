<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
Route::get('/', function () {
    return view('welcome');
});
*/

Route::get('/', [ProductController::class, 'index']);

Route::post('/add-to-cart', [CartController::class, 'add_to_cart'])->name('add-to-cart');

Route::post('/remove-from-cart', [CartController::class, 'remove_from_cart'])->name('remove-from-cart');


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
