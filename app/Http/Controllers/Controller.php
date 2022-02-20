<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * ユーザ情報を取得(ユーザID, IPアドレス, ユーザエージェント)
     */
    protected function getUserInfo($request)
    {
        if (Auth::check()) {
            // ログイン済みの場合
            return ' :: [ user_id: '.Auth::id().' , IP Adress: '.$request->ip().' , UserAgent: '.$request->header('User-Agent').' ]';
        }
        // ログアウトされている場合
        return ' :: [ user_id: already logout , IP Adress: '.$request->ip().' , UserAgent: '.$request->header('User-Agent').' ]';
    }

    /**
     * エラー用のログを出力
     * $request  -- Http\Requestクラスのインスタンス, 
     * $e        -- Exceptionクラスのインスタンス, 
     * $class    -- 実行中のクラス名, 
     * $function -- 実行中のメソッド名 
     */
    protected function getErrorLog($request, $e, $class, $function)
    {
        Log::error(config('const.SystemMessage.SYSTEM_ERR').$class.'::'.$function.":".$e->getMessage(). $this->getUserInfo($request));
    }
}
