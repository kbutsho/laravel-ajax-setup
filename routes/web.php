<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ProductController::class, 'show'])->name('home');
Route::get('get-product', [ProductController::class, 'getProducts'])->name('get.product');
Route::post('add-product', [ProductController::class, 'addProduct'])->name('add.product');
Route::post('update-product', [ProductController::class, 'updateProduct'])->name('update.product');
Route::post('delete-product', [ProductController::class, 'deleteProduct'])->name('delete.product');