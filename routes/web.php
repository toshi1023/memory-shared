<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/************************************************
 *  アプリ側ルーティング(非ログイン)
 ************************************************/
Route::group(['middleware' => 'cors'], function() {
    Route::post('/validate',                'Api\UserController@webValidate');
    Route::post('/register',                'Api\UserController@store')->name('register');
    Route::post('/login',                   'Api\AuthController@login')->name('login');
    Route::post('/forgot-password',         'Api\AuthController@forgotPassword')->name('forgotPassword');
    Route::post('/reset-password/{email}/{token}',  'Api\AuthController@passwordReset')->name('passwordReset');
    Route::post('/logout',                  'Api\AuthController@logout')->name('logout');
});
