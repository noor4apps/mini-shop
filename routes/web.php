<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

/*
Route::get('/', function () {
    return view('welcome');
});
*/

Route::get('/', [ProductController::class, 'index']);

Route::post('/add-to-cart', [CartController::class, 'add_to_cart'])->name('add-to-cart');

Route::post('/remove-from-cart', [CartController::class, 'remove_from_cart'])->name('remove-from-cart');

Route::get('/cart', [CartController::class, 'show_cart_page'])->name('carts.index');

Route::post('/cart-update', [CartController::class,'update_item_qty'])->name('carts.update');

Route::get('/checkout', [OrderController::class ,'checkout'])->name('checkout.index');

Route::post('/order', [OrderController::class ,'store'])->name('place-order');

Route::get('/order-placed/{order}', [OrderController::class, 'order_placed'])->name('order-placed');


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
