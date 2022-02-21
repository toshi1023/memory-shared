<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Lib\SlackFacade;

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
            // return ' :: [ user_id: '.Auth::id().' , IP Adress: '.$request->ip().' , UserAgent: '.$request->header('User-Agent').' ]';
            return ' :: [ user_id: '.Auth::id().' , IP Adress: 0.0.0.0 , UserAgent: '.$request->header('User-Agent').' ]';
        }
        // ログアウトされている場合
        // return ' :: [ user_id: already logout , IP Adress: '.$request->ip().' , UserAgent: '.$request->header('User-Agent').' ]';
        return ' :: [ user_id: already logout , IP Adress: 0.0.0.0 , UserAgent: '.$request->header('User-Agent').' ]';
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
        $msg = config('const.SystemMessage.SYSTEM_ERR').$class.'::'.$function.' : '.$e->getMessage(). $this->getUserInfo($request);
        $msg2 = '';

        // 標準メッセージをLogに出力
        Log::error($msg);

        // stack traceをLogに出力
        $index = 1;
        foreach($e->getTrace() as $val) {
            // 例) StackTrace[1] :: /home/test/app/Http/Controllers/TestController.php 22行目, { class: Test , function: test }
            $trace = 'StackTrace['.$index.'] :: '.$val["file"].' '.$val["line"].'行目 , { class: '.$val["class"].' , function: '.$val["function"].' }';
            Log::error($trace);

            if($index === 1) $msg2 = $trace;

            $index += 1;
        }

        // Slackに通知
        SlackFacade::send(config('const.SystemMessage.SLACK_LOG_WARN').$msg.' , '.$msg2);
    }
}
