<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/





Route::post('login', 'Api\V1\AuthController@login');
Route::post('register', 'Api\V1\AuthController@register');

Route::group(['middleware' => 'auth:api'], function(){
    Route::post('profile', 'Api\V1\AuthController@profile');
    Route::post('product/store', 'Api\V1\ProductController@store');

});

Route::get('products', 'Api\V1\ProductController@index');
Route::get('product/{id}', 'Api\V1\ProductController@show'); // self  + user

