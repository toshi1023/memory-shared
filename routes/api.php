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
Route::get('/login', 'Api\UserController@index');

 /************************************************
 *  アプリ側ルーティング(ログイン)
 ************************************************/
Route::middleware('auth:sanctum')->group(function(){

    Route::get('/users', 'Api\UserController@index');
    Route::get('/albums', 'Api\AlbumController@index');

});