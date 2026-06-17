<?php

use App\Http\Controllers\TestController;
use App\Http\Controllers\WishlistController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Product Routes (TestController)
|--------------------------------------------------------------------------
*/
Route::get('/products', [
    TestController::class,
    'getProducts'
]);

Route::get('/products/{id}', [
    TestController::class,
    'getProductById'
]);

Route::post('/products', [
    TestController::class,
    'createProduct'
])->withoutMiddleware([VerifyCsrfToken::class]);

Route::patch('/products/{id}', [
    TestController::class,
    'updateProduct'
])->withoutMiddleware([VerifyCsrfToken::class]);

Route::delete('/products/{id}', [
    TestController::class,
    'deleteProduct'
])->withoutMiddleware([VerifyCsrfToken::class]);

/*
|--------------------------------------------------------------------------
| Wishlist Routes (WishlistController)
|--------------------------------------------------------------------------
*/
Route::get('/wishlist', [
    WishlistController::class,
    'getWishlistItems'
]);

Route::get('/wishlist/{id}', [
    WishlistController::class,
    'getWishlistItemById'
]);

Route::post('/wishlist', [
    WishlistController::class,
    'createWishlistItem'
])->withoutMiddleware([VerifyCsrfToken::class]);

Route::patch('/wishlist/{id}', [
    WishlistController::class,
    'updateWishlistItem'
])->withoutMiddleware([VerifyCsrfToken::class]);

Route::delete('/wishlist/{id}', [
    WishlistController::class,
    'deleteWishlistItem'
])->withoutMiddleware([VerifyCsrfToken::class]);
