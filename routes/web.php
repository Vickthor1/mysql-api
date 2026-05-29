<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::get('/', function () {
    return view('home');
});

Route::get('/products',        [ProductController::class, 'webIndex']);
Route::get('/products/create', [ProductController::class, 'create']);
Route::post('/products',       [ProductController::class, 'webStore']);
Route::get('/import-product',  [ProductController::class, 'import']);
