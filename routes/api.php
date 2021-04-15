<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

/************************************************
 *  アプリ側ルーティング(非ログイン)
 ************************************************/
Route::post('/login', 'Api\AuthController@login')->name('login');
Route::post('/reset-password/{token}', 'Api\AuthController@passwordReset')->name('passwordReset');
Route::post('/logout', 'Api\AuthController@logout')->name('logout');

 /************************************************
 *  アプリ側ルーティング(ログイン)
 ************************************************/
Route::middleware('auth:sanctum')->group(function(){

    /********** ユーザ管理(users) **********/
    Route::resource('/users',       'Api\UserController');
    Route::post('/users/validate',  'Api\UserController@userValidate');
    
    /********** アルバム管理(albums) **********/
    Route::get('/albums', 'Api\AlbumController@index');
    

});