<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Repositories\User\UserRepositoryInterface;
use App\Lib\Common;
use App\Jobs\SendEmail;

class AuthController extends Controller
{
    /**
     * ログイン処理
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request) 
    {
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
            if($this->getGuard()->attempt($credentials)) {
                // 管理者の場合はワンタイムパスワードを保存
                if(Auth::user()->status === config('const.User.ADMIN')) {
                    // ワンタイムパスワード発行
                    $onePass = Common::issueOnetimePassword(false);
                    // ワンタイムパスワードを保存
                    $repository = app()->make(UserRepositoryInterface::class);
                    $repository->saveOnePass($onePass, Auth::user()->id);
                    // ワンタイムパスワードの通知メールを送信
                    SendEmail::dispatch(['id' => Auth::user()->id, 'email' => Auth::user()->email]);
                }

                return response()->json([
                    "id"           => Auth::user()->id,
                    "name"         => Auth::user()->name,
                    "info_message" => config('const.SystemMessage.LOGIN_INFO')
                ], 200, [], JSON_UNESCAPED_UNICODE);
            }

            // 認証に失敗した場合
            return response()->json(["error_message" => config('const.SystemMessage.LOGIN_ERR')], 401, [], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            Log::error(config('const.SystemMessage.SYSTEM_ERR').get_class($this).'::'.__FUNCTION__.":".$e->getMessage());

            // 認証に失敗した場合
            return response()->json(["error_message" => config('const.SystemMessage.LOGIN_ERR')], 401, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * ログアウト処理
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logout(Request $request) 
    {
        try {
            if((int)$request->id === Auth::user()->id) {
                // 管理者の場合はワンタイムパスワードを削除
                if(Auth::user()->status === 3) {
                    // ワンタイムパスワードを削除
                    $repository = app()->make(UserRepositoryInterface::class);
                    $repository->saveOnePass(null, Auth::user()->id);
                }

                $this->getGuard()->logout();

                return response()->json(["info_message" => config('const.SystemMessage.LOGOUT_INFO')], 200, [], JSON_UNESCAPED_UNICODE);
            }
        } catch (Exception $e) {
            Log::error(config('const.SystemMessage.SYSTEM_ERR').get_class($this).'::'.__FUNCTION__.":".$e->getMessage());

            return response()->json(["error_message" => config('const.SystemMessage.UNEXPECTED_ERR')], 200, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * @return StatefulGuard
     */
    private function getGuard()
    {
        return Auth::guard(config('auth.defaults.guard'));
    }
}
