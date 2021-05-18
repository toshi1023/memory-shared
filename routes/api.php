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
// Route::resource('/users',       'Api\UserController');
// Route::get('/users/{user}/friends', 'Api\UserController@friends')->name('users.friends');
// Route::get('/users/{user}/groups',  'Api\UserController@participating')->name('users.participating');
// Route::resource('/groups',       'Api\GroupController');
// Route::resource('/groups/{group}/albums',       'Api\AlbumController');
// Route::post('/albums/validate',  'Api\AlbumController@albumValidate');
// Route::resource('/groups/{group}/albums/{album}/image',       'Api\UserImageController')->only('store', 'destroy');

 /************************************************
 *  アプリ側ルーティング(ログイン)
 ************************************************/
Route::middleware('auth:sanctum')->group(function(){

    /********** ユーザ管理(users) **********/
    Route::resource('/users',           'Api\UserController');
    Route::get('/users/{user}/friends', 'Api\UserController@friends')->name('users.friends');
    Route::get('/users/{user}/groups',  'Api\UserController@participating')->name('users.participating');
    Route::post('/users/validate',      'Api\UserController@userValidate');
    
    /********** グループ管理(groups) **********/
    Route::resource('/groups',       'Api\GroupController');
    Route::post('/groups/validate',  'Api\GroupController@groupValidate');
    
    /********** アルバム管理(albums) **********/
    Route::resource('/groups/{group}/albums',       'Api\AlbumController');
    Route::post('/groups/{group}/albums/validate',  'Api\AlbumController@albumValidate');
    
    /********** 画像管理(user_images) **********/
    Route::resource('/groups/{group}/albums/{album}/image',       'Api\UserImageController')->only('store', 'destroy');
    Route::post('/groups/{group}/albums/{album}/image/validate',  'Api\UserImageController@userImageValidate');
    
    /********** 動画管理(user_videos) **********/
    Route::resource('/groups/{group}/albums/{album}/video',       'Api\UserVideoController')->only('store', 'destroy');
    Route::post('/groups/{group}/albums/{album}/video/validate',  'Api\UserVideoController@userVideoValidate');

    /********** ニュース管理(news) **********/
    Route::resource('/news',       'Api\NewsController');
    Route::post('/news/validate',  'Api\NewsController@newsValidate');
});