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
Route::resource('/users',       'Api\UserController');
// Route::get('/users/{user}/families', 'Api\UserController@families')->name('users.families');
// Route::get('/users/{user}/groups',  'Api\UserController@participating')->name('users.participating');
// Route::get('/users/{user}/messages', 'Api\UserController@messages')->name('users.messages');
// Route::resource('/groups',       'Api\GroupController');
// Route::resource('/groups/{group}/albums',       'Api\AlbumController');
// Route::post('/albums/validate',  'Api\AlbumController@albumValidate');
// Route::resource('/groups/{group}/albums/{album}/images',       'Api\UserImageController')->only('store', 'destroy');
// Route::resource('/messages',       'Api\MessageHistoryController')->only('index', 'store', 'destroy');
// Route::post('/groups/{group}/history',       'Api\GroupHistoryController@store');
// Route::put('/groups/{group}/history',       'Api\GroupHistoryController@update');
// Route::resource('/news',       'Api\NewsController');
// Route::post('users/{user}/messages/mread',       'Api\MreadManagementController@destroy');
// Route::post('/news/{news}/nread',       'Api\NreadManagementController@destroy');
// Route::resource('/groups/{group}/posts',       'Api\PostController')->only('index', 'store', 'destroy');
// Route::resource('/groups/{group}/posts/{post}/comments',       'Api\PostCommentController')->only('index', 'store', 'destroy');

 /************************************************
 *  アプリ側ルーティング(ログイン)
 ************************************************/
Route::middleware('auth:sanctum')->group(function(){

    /********** ユーザ管理(users) **********/
    // Route::resource('/users',           'Api\UserController');
    Route::get('/users/{user}/families', 'Api\UserController@families')->name('users.families');
    Route::get('/users/{user}/groups',  'Api\UserController@participating')->name('users.participating');
    Route::get('/users/{user}/messages', 'Api\UserController@messages')->name('users.messages');
    Route::post('/users/validate',      'Api\UserController@userValidate');
    
    /********** グループ管理(groups) **********/
    Route::resource('/groups',       'Api\GroupController');
    Route::post('/groups/validate',  'Api\GroupController@groupValidate');

    /********** グループ履歴管理(group_histories) **********/
    Route::post('/groups/{group}/history',       'Api\GroupHistoryController@store');
    Route::put('/groups/{group}/history',       'Api\GroupHistoryController@update');
    
    /********** アルバム管理(albums) **********/
    Route::resource('/groups/{group}/albums',       'Api\AlbumController');
    Route::post('/groups/{group}/albums/validate',  'Api\AlbumController@albumValidate');
    
    /********** 画像管理(user_images) **********/
    Route::resource('/groups/{group}/albums/{album}/images',      'Api\UserImageController')->only('store', 'destroy');
    Route::post('/groups/{group}/albums/{album}/image/validate',  'Api\UserImageController@userImageValidate');
    
    /********** 動画管理(user_videos) **********/
    Route::resource('/groups/{group}/albums/{album}/videos',      'Api\UserVideoController')->only('store', 'destroy');
    Route::post('/groups/{group}/albums/{album}/video/validate',  'Api\UserVideoController@userVideoValidate');

    /********** メッセージ管理(messages) **********/
    Route::resource('/messages',       'Api\MessageHistoryController')->only('index', 'store', 'destroy');
    Route::post('/messages/validate',  'Api\MessageHistoryController@messageValidate');

    /********** メッセージ用未読管理(mread_managements) **********/
    Route::post('users/{user}/messages/mread',       'Api\MreadManagementController@destroy');

    /********** ニュース管理(news) **********/
    Route::resource('/news',       'Api\NewsController');
    Route::post('/news/validate',  'Api\NewsController@newsValidate');

    /********** ニュース用未読管理(nread_managements) **********/
    Route::post('/news/{news}/nread',       'Api\NreadManagementController@destroy');

    /********** 投稿管理(posts) **********/
    Route::resource('/groups/{group}/posts',       'Api\PostController')->only('index', 'store', 'destroy');

    /********** 投稿のコメント管理(post_comments) **********/
    Route::resource('/groups/{group}/posts/{post}/comments',       'Api\PostCommentController')->only('index', 'store', 'destroy');
});