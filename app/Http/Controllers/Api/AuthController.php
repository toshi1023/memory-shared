<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use AuthenticatesUsers;

    /**
     * ログイン処理
     * @return \Illuminate\Http\RedirectResponse
     */
    public function attemptLogin(Request $request) {
        // ステータス「管理者(システムアドミン)」
        if ($this->customAttempt($request)) {
            return true;
        }
        return false;
    }

    /**
     * ログアウト処理
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logout(Request $request) {
        $this->guard()->logout();
        $request->session()->invalidate();
        return redirect('/');
    }

    /**
     * カスタム認証
     * ※status: 管理者と会員のみログインOK
     * @param Request $request
     * @return bool
     */
    private function customAttempt(Request $request) {
        if ($this->guard()->attempt(
            [
                'email' => $request->input('email'),
                'password' => $request->input('password'),
                'status'   => config('const.User.MEMBER') || config('const.User.ADMIN'),
                'deleted_at' => null
            ], $request->filled('remember'))
        ) {
            return true;
        }
        return false;
    }
}
