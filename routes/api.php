<?php

use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\Admin\AttributeController;
use App\Http\Controllers\Api\Admin\AttributeValueController;
use App\Http\Controllers\Api\Admin\AuthController;
use App\Http\Controllers\Api\Admin\CategoryController;
use App\Http\Controllers\Api\Admin\ProductController;
use App\Http\Controllers\Api\Admin\ProductItemController;
use App\Http\Controllers\Api\Admin\VariationController;
use App\Http\Controllers\Api\Admin\VariationOptionController;
use App\Http\Controllers\Api\CartItemController;
use App\Http\Controllers\Api\GoshipController;
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

Route::post('/login',[AuthController::class,'login']);


Route::group(['prefix' => 'admin'],function (){
    Route::group(['middleware'=>['auth:sanctum']],function () {
        Route::get('/category', [CategoryController::class, 'index']);
        Route::get('/category/{id}', [CategoryController::class, 'show']);
        Route::post('/category', [CategoryController::class, 'store']);
        Route::put('/category/{id}', [CategoryController::class, 'update']);
        Route::delete('/category/{id}', [CategoryController::class, 'destroy']);

        Route::get('/attribute',[AttributeController::class,'index']);
        Route::post('/attribute', [AttributeController::class, 'store']);
        Route::get('/attribute/{id}',[AttributeController::class,'show']);
        Route::put('/attribute/{id}', [AttributeController::class, 'update']);
        Route::delete('/attribute/{id}', [AttributeController::class, 'destroy']);
        Route::get('/attribute-category/{id}',[AttributeController::class,'getByCategory']);


        Route::post('/attribute-value', [AttributeValueController::class, 'store']);
        Route::put('/attribute-value/{id}', [AttributeValueController::class, 'update']);
        Route::delete('/attribute-value/{id}', [AttributeValueController::class, 'destroy']);

        Route::get('/product', [ProductController::class, 'index']);
        Route::post('/product', [ProductController::class, 'store']);
        Route::get('/product/{id}', [ProductController::class, 'show']);
        Route::post('/product/{id}', [ProductController::class, 'update']);
        Route::delete('/product/{id}', [ProductController::class, 'destroy']);
        Route::put('/product/{id}',[ProductController::class,'changeStatus']);

        Route::delete('/product-item/{id}',[ProductItemController::class,'destroy']);

        Route::post('/variation',[VariationController::class,'store']);
        Route::delete('/variation/{id}',[VariationController::class,'destroy']);

        Route::post('/variation-option', [VariationOptionController::class, 'store']);
        Route::delete('/variation-option/{id}', [VariationOptionController::class, 'destroy']);

    });
    
});

Route::group(['middleware' => ['auth:sanctum']],function (){

    Route::get('/authorize',[AuthController::class,'authorizeAdmin']);
    Route::get('/logout',[AuthController::class,'logout']);

    //api cart
    Route::get('/cart',[CartItemController::class,'index']);
    Route::post('/cart',[CartItemController::class,'store']);
    Route::put('/cart/{id}',[CartItemController::class,'update']);
    Route::delete('/cart/{id}',[CartItemController::class,'destroy']);
    Route::post('/buy-now',[CartItemController::class,'buyNow']);

    //api goship
    Route::get('get-cities',[GoshipController::class,'getCities']);
    Route::get('get-districts/{id}',[GoshipController::class,'getDistrictsByCity']);
    Route::get('get-wards/{id}',[GoshipController::class,'getWardsByDistrict']);

    //api address
    Route::post('address',[AddressController::class,'store']);
    Route::post('address/{id}',[AddressController::class,'update']);
    Route::delete('address/{id}',[AddressController::class,'destroy']);

});

Route::get('/test',[ProductController::class,'getSlug']);