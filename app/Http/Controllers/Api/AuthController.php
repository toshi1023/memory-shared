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
     * @OA\Post(
     *     path="/login",
     *     description="ログインを実行する",
     *     produces={"application/json"},
     *     tags={"auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="request",
     *                 type="object",
     *                 description="リクエストボディのjsonのプロパティの例",
     *                 require={"status", "email", "password"},
     *                 @OA\Property(property="status", type="integer", example=1, description="2,4はアカウント停止系の値のため、ログインエラーを返すように設定する"),
     *                 @OA\Property(property="email", type="string", example="test@xxx.co.jp"),
     *                 @OA\Property(property="password", type="string", example="test1234"),
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success / ログインが正常に完了したメッセージとログインユーザのid,nameを返す(管理者ユーザの場合はワンタイムパスワードを発行してメール送信も実施する)",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="info_message",
     *                 type="string",
     *                 description="ログインが正常に完了したメッセージを表示",
     *                 example="ログインに成功しました"
     *             ),
     *             @OA\Property(
     *                 property="id",
     *                 type="integer",
     *                 description="ログインユーザのidを表示",
     *                 example=2
     *             ),
     *             @OA\Property(
     *                 property="name",
     *                 type="string",
     *                 description="ログインユーザのnameを表示",
     *                 example="test name"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error / サーバエラー用のメッセージを表示",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="error_message",
     *                 type="string",
     *                 description="サーバエラー用のメッセージを表示",
     *                 example="メールアドレスもしくはパスワードが一致しません"
     *             )
     *         )
     *     ),
     * )
     * 
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
     * @OA\Post(
     *     path="/logout",
     *     description="ログアウトを実行する",
     *     produces={"application/json"},
     *     tags={"auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="request",
     *                 type="object",
     *                 description="リクエストボディのjsonのプロパティの例",
     *                 require={"id"},
     *                 @OA\Property(property="id", type="integer", example=2),
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success / ログアウトが正常に完了したメッセージを返す(管理者ユーザの場合はDBからワンタイムパスワードの削除も実施する)",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="info_message",
     *                 type="string",
     *                 description="ログアウトが正常に完了したメッセージを表示",
     *                 example="ログアウトしました"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error / サーバエラー用のメッセージを表示",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="error_message",
     *                 type="string",
     *                 description="サーバエラー用のメッセージを表示",
     *                 example="予期しないエラーが発生しました。管理者にお問い合わせください"
     *             )
     *         )
     *     ),
     * )
     * 
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
