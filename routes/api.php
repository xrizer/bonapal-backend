<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function(){
    Route::post('/login',[App\Http\Controllers\Api\Admin\LoginController::class,'index',['as'=>'admin']]);
    Route::group(['middleware' => 'auth:api_admin'],function(){
        Route::get('/user',[App\Http\Controllers\Api\Admin\LoginController::class,'getUser',['as' =>'admin']]);
        Route::get('/refresh',[App\Http\Controllers\Api\Admin\LoginController::class,'refreshToken',['as'=>'admin']]);
        Route::post('/logout',[App\Http\Controllers\Api\Admin\LoginController::class,'logout',['as'=>'admin']]);
        Route::get('/dashboard',[\App\Http\Controllers\Api\Admin\DashboardController::class,'index',['as' => 'admin']]);
        Route::apiResource('/categories', App\Http\Controllers\Api\Admin\CategoryController::class, ['except' => ['create', 'edit'], 'as' => 'admin']);
        Route::apiResource('/products', App\Http\Controllers\Api\Admin\ProductController::class, ['except' => ['create', 'edit'], 'as' => 'admin']);
        Route::apiResource('/invoices', App\Http\Controllers\Api\Admin\InvoiceController::class, ['except' => ['create','store','update','destroy','edit'], 'as' => 'admin']);
        Route::get('/customers',[App\Http\Controllers\Api\Admin\CustomerController::class,'index',['as'=>'admin']]);
        Route::apiResource('/sliders', App\Http\Controllers\Api\Admin\SliderController::class, ['except' => ['create','update','show','edit'], 'as' => 'admin']);
        Route::apiResource('/users',App\Http\Controllers\Api\Admin\UserController::class,['except'=>['create','edit'],'as'=>'admin']);
    });
});

Route::prefix('customer')->group(function () {

    //route register
    Route::post('/register', [App\Http\Controllers\Api\Customer\RegisterController::class, 'store'], ['as' => 'customer']);
    Route::post('/login',[App\Http\Controllers\Api\Customer\LoginController::class,'index'],['as'=>'customer']);
    Route::group(['middleware' =>'auth:api_customer'],function(){
        Route::get('/user',[App\Http\Controllers\Api\Customer\LoginController::class,'getUser'],['as'=>'customer']);
        Route::get('/refresh',[App\Http\Controllers\Api\Customer\LoginController::class,'refreshToken'],['as'=>'customer']);
        Route::post('/logout',[App\Http\Controllers\Api\Customer\LoginController::class,'logout'],['as'=>'customer']);
        Route::get('/dashboard',[App\Http\Controllers\Api\Customer\DashboardController::class,'index'],['as'=>'customer']);
        Route::apiResource('/invoices',\App\Http\Controllers\Api\Customer\InvoiceController::class,['except'=>['create','store','edit','update','destroy'],'as'=>'customer']);
        Route::post('/reviews',[\App\Http\Controllers\Api\Customer\ReviewController::class],'store',['as'=>'customer']);
    });
});

Route::prefix('web')->group(function (){
    Route::apiResource('/categories', App\Http\Controllers\Api\Web\CategoryController::class,['except'=>['create', 'store', 'edit', 'update', 'destroy'],'as' => 'web']);
    Route::apiResource('/products',App\Http\Controllers\Api\Web\ProductController::class,['except' => ['create','store','edit','update','destroy'],'as'=>'web']);
    Route::get('/sliders',[App\Http\Controllers\Api\Web\SliderController::class,'index'],['as'=>'web']);
    Route::get('/rajaongkir/provinces',[App\Http\Controllers\Api\Web\RajaOngkirController::class,'getProvinces'],['as' =>'web']);
    Route::post('/rajaongkir/cities',[App\Http\Controllers\Api\Web\RajaOngkirController::class,'getCities'],['as' =>'web']);
    Route::post('/rajaongkir/checkOngkir',[App\Http\Controllers\Api\Web\RajaOngkirController::class,'checkOngkir'],['as'=>'web']);
    Route::get('/carts',[App\Http\Controllers\Api\Web\CartController::class,'index'],['as'=>'web']);
    Route::post('/carts',[App\Http\Controllers\Api\Web\CartController::class,'store'],['as'=>'web']);
    Route::get('/carts/total_price',[App\Http\Controllers\Api\Web\CartController::class,'getCartPrice'],['as'=>'web']);
    Route::get('/carts/total_weight',[App\Http\Controllers\Api\Web\CartController::class,'getCartWeight'],['as'=>'web']);
    Route::post('/carts/remove',[App\Http\Controllers\Api\Web\CartController::class,'removeCart'],['as'=>'web']);
    Route::post('/checkout',[App\Http\Controllers\Api\Web\CheckoutController::class,'store'],['as'=>'web']);
    Route::post('/notification',[App\Http\Controllers\Api\Web\NotificationHandlerController::class,'index'],['as'=>'web']);
});

