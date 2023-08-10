<?php

use App\Http\Controllers\Api\Admin\AuthController;
use App\Http\Controllers\Api\Admin\CategoryController;
use App\Http\Controllers\Api\Admin\ProductController;
use App\Http\Controllers\Api\Admin\VariationController;
use App\Http\Controllers\Api\Admin\VariationOptionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['middleware'=>'auth:sanctum'],function (){
    Route::get('/category', [CategoryController::class, 'index']);
    Route::get('/category/{id}', [CategoryController::class, 'show']);
    Route::post('/category', [CategoryController::class, 'store']);
    Route::put('/category/{id}', [CategoryController::class, 'update']);
    Route::delete('/category/{id}', [CategoryController::class, 'destroy']);

    Route::post('/variation', [VariationController::class, 'store']);
    Route::put('/variation/{id}', [VariationController::class, 'update']);
    Route::delete('/variation/{id}', [VariationController::class, 'destroy']);

    Route::get('/product', [ProductController::class, 'index']);
    Route::post('/product', [ProductController::class, 'store']);
    Route::get('/product/{id}', [ProductController::class, 'show']);
    Route::post('/product/{id}', [ProductController::class, 'update']);
    Route::delete('/product/{id}', [ProductController::class, 'destroy']);

    Route::post('/variation-option', [VariationOptionController::class, 'store']);
    Route::delete('/variation-option/{id}', [VariationOptionController::class, 'destroy']);

    Route::get('/logout',[AuthController::class,'logout']);
});

Route::post('/login',[AuthController::class,'login']);
// Route::post('/register',[AuthController::class,'register']);