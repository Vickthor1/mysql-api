<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SearchController;

Route::get('/', function () {
    return view('home');
});

Route::get('/products',        [SearchController::class, 'search']);
Route::get('/products/create', [ProductController::class, 'create']);
Route::post('/products',       [ProductController::class, 'webStore']);
Route::get('/api-info', function () {
    return view('api-info');
});
Route::get('/import-product',  [ProductController::class, 'import']);
