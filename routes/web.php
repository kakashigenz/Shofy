<?php

use App\Http\Controllers\Web\AddressController;
use App\Http\Controllers\Web\CartItemController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\OrderController;
use App\Http\Controllers\Web\ProductController;
use App\Http\Controllers\Web\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::view('/{path}', 'app')->where('path', "^admin.*");

Route::get('/',[HomeController::class,'index'])->name('home.index');
Route::get('/login',function (){
    if (auth('sanctum')->check()){
        return redirect('/');
    }
    return view('auth.login',['title' => 'Đăng nhập']);
})->name('login');
Route::get('/cart',[CartItemController::class,'show'])->name('cart');
Route::get('/user',[UserController::class,'show'])->name('profile');
Route::group(['prefix'=>'user'],function (){
    Route::get('/address',[AddressController::class,'index'])->name('address.index');
    Route::get('/create-address',[AddressController::class,'store'])->name('address.store');
    Route::get('/update-address/{id}',[AddressController::class,'update'])->name('address.update');

});
Route::get('/create-order',[OrderController::class,'store'])->name('order.store');
Route::get('/{slug}',[ProductController::class,'show'])->name('product.show');