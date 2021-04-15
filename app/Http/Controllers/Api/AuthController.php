<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    /**
     * ログイン処理
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request) {
        try {
            // statusチェック
            if($request->status === config('const.User.UNSUBSCRIBE') || $request->status === config('const.User.STOP')) {
                return response()->json(["error_message" => config('const.SystemMessage.UNAUTHORIZATION')], 401, [], JSON_UNESCAPED_UNICODE);
            }
            
            // 認証処理
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);
            if(Auth::attempt($credentials)) {
                return response()->json(["user" => Auth::user(), "info_message" => config('const.SystemMessage.LOGIN_INFO')], 200, [], JSON_UNESCAPED_UNICODE);
            }

            // 認証に失敗した場合
            return response()->json(["error_message" => config('const.SystemMessage.LOGIN_ERR')], 401, [], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            Log::error(config('const.SystemMessage.SYSTEM_ERR').get_class($this).'::'.__FUNCTION__.":".$e->getMessage());
        }
    }

    /**
     * ログアウト処理
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logout(Request $request) {
        try {
            if($request->id === Auth::user()->id) {
                Auth::logout();
                return response()->json(["info_message" => config('const.SystemMessage.LOGOUT_INFO')], 200, [], JSON_UNESCAPED_UNICODE);
            }
        } catch (Exception $e) {
            Log::error(config('const.SystemMessage.SYSTEM_ERR').get_class($this).'::'.__FUNCTION__.":".$e->getMessage());
        }
    }
}
