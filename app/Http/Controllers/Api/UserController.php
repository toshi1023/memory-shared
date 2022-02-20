<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\User\UserRepositoryInterface;
use App\Http\Requests\UserRegisterRequest;
use App\Lib\Common;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    protected $db;

    public function __construct(UserRepositoryInterface $database)
    {
        $this->db = $database;
    }

    /**
     * @OA\Schema(
     *     schema="user_list",
     *     required={"id", "name", "hobby", "gender", "description", "status", "image_file"},
     *     @OA\Property(property="id", type="integer", example=1),
     *     @OA\Property(property="name", type="string", example="test1"),
     *     @OA\Property(property="hobby", type="string", example="野球観戦"),
     *     @OA\Property(property="gender", type="integer", example="0"),
     *     @OA\Property(property="description", type="string", example="仲良くしましょう！よろしく！"),
     *     @OA\Property(property="status", type="integer", example="1"),
     *     @OA\Property(property="image_file", type="string", example="xxxxoooo.png"),
     * )
     */
    /**
     * @OA\Schema(
     *     schema="user_detail",
     *     required={"id", "name", "hobby", "gender", "description", "email", "status", "image_file"},
     *     @OA\Property(property="id", type="integer", example=1),
     *     @OA\Property(property="name", type="string", example="test1"),
     *     @OA\Property(property="hobby", type="string", example="野球観戦"),
     *     @OA\Property(property="gender", type="integer", example="0"),
     *     @OA\Property(property="description", type="string", example="仲良くしましょう！よろしく！"),
     *     @OA\Property(property="email", type="string", example="test1@xxx.co.jp"),
     *     @OA\Property(property="status", type="integer", example="1"),
     *     @OA\Property(property="image_file", type="string", example="xxxxoooo.png"),
     * )
     */
    /**
     * @OA\Schema(
     *     schema="user_register",
     *     required={"name", "email", "password", "password_confirmation", "user_agent", "image_file"},
     *     @OA\Property(property="name", type="string", example="test1"),
     *     @OA\Property(property="email", type="string", example="test1@xxx.co.jp"),
     *     @OA\Property(property="password", type="string", example="test1234"),
     *     @OA\Property(property="password_confirmation", type="string", example="test1234"),
     *     @OA\Property(property="user_agent", type="string", example="Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_8_8) AppleWebKit/5330 (KHTML, like Gecko) Chrome/36.0.833.0 Mobile Safari/5330"),
     *     @OA\Property(property="image_file", type="string", example="xxxxoooo.png"),
     * )
     */
    /**
     * @OA\Schema(
     *     schema="user_update",
     *     required={"id", "name", "email", "password", "password_confirmation", "hobby", "gender", "description", "user_agent", "image_file"},
     *     @OA\Property(property="id", type="integer", example=1),
     *     @OA\Property(property="name", type="string", example="test1"),
     *     @OA\Property(property="email", type="string", example="test1@xxx.co.jp"),
     *     @OA\Property(property="password", type="string", example="test1234"),
     *     @OA\Property(property="password_confirmation", type="string", example="test1234"),
     *     @OA\Property(property="hobby", type="string", example="野球観戦"),
     *     @OA\Property(property="gender", type="integer", example="0"),
     *     @OA\Property(property="description", type="string", example="仲良くしましょう！よろしく！"),
     *     @OA\Property(property="user_agent", type="string", example="Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_8_8) AppleWebKit/5330 (KHTML, like Gecko) Chrome/36.0.833.0 Mobile Safari/5330"),
     *     @OA\Property(property="image_file", type="string", example="xxxxoooo.png"),
     * )
     */
    /**
     * @OA\Schema(
     *     schema="user_errors",
     *     required={"name", "email", "password", "password_confirmation", "image_file"},
     *     @OA\Property(property="name", type="object", required={"required", "unique", "name.max"},
     *          @OA\Property(property="required", type="string", example="ユーザ名は必須です"),
     *          @OA\Property(property="unique", type="string", example="このユーザ名はすでに使用されています"),
     *          @OA\Property(property="name.max", type="string", example="ユーザ名は15文字以内で入力してください"),
     *     ),
     *     @OA\Property(property="email", type="object", required={"required", "email", "email.regex", "max"},
     *          @OA\Property(property="required", type="string", example="メールアドレスは必須です"),
     *          @OA\Property(property="email", type="string", example="メールアドレスの書式のみ有効です"),
     *          @OA\Property(property="email.regex", type="string", example="@以前は半角英数字で入力してください"),
     *          @OA\Property(property="email.max", type="string", example="メールアドレスは50文字以内で入力してください"),
     *     ),
     *     @OA\Property(property="password", type="object", required={"required", "min", "confirmed", "password.regex"},
     *          @OA\Property(property="required", type="string", description="ユーザの新規登録時のみ適用", example="パスワードは必須です"),
     *          @OA\Property(property="password.min", type="string", example="パスワードは6文字以上で入力してください"),
     *          @OA\Property(property="confirmed", type="string", example="パスワードを確認用と一致させてください"),
     *          @OA\Property(property="password.regex", type="string", example="パスワードは半角英数字及び「_@!?#%&」の記号のみで入力してください"),
     *     ),
     *     @OA\Property(property="password_confirmation", type="object", required={"required", "min", "password_confirmation.regex"},
     *          @OA\Property(property="required", type="string", description="ユーザの新規登録時のみ適用", example="パスワード(確認用)は必須です"),
     *          @OA\Property(property="password_confirmation.min", type="string", example="パスワード（確認用）は6文字以上で入力してください"),
     *          @OA\Property(property="password_confirmation.regex", type="string", example="パスワード（確認）は半角英数字及び「_@!?#%&」の記号のみで入力してください"),
     *     ),
     *     @OA\Property(property="image_file", type="object", required={"mimes", "image_file.max"},
     *          @OA\Property(property="mimes", type="string", example="アップロードファイルはjpeg,png,jpg,gifタイプのみ有効です"),
     *          @OA\Property(property="image_file.max", type="string", example="1Mを超えています。"),
     *     ),
     * )
     */
    /**
     * @OA\Schema(
     *     schema="user_groups",
     *     required={"id", "name", "description", "private_flg", "welcome_flg", "image_file", "host_user_id", "created_at", "updated_at", "albums", "groupHistories"},
     *     @OA\Property(property="id", type="integer", example=1),
     *     @OA\Property(property="name", type="string", example="test group 1"),
     *     @OA\Property(property="description", type="string", example="盛り上げていきましょう！"),
     *     @OA\Property(property="private_flg", type="integer", example="0"),
     *     @OA\Property(property="welcome_flg", type="integer", example="1"),
     *     @OA\Property(property="image_file", type="string", example="xxxxoooo.png"),
     *     @OA\Property(property="host_user_id", type="number", example="1"),
     *     @OA\Property(property="created_at", type="string", example="2021-04-25 12:02:55"),
     *     @OA\Property(property="updated_at", type="string", example="2021-04-28 14:13:00"),
     *     @OA\Property(property="albums", type="object", description="albumsテーブルとのリレーションデータ", required={"id", "name", "group_id"},
     *         @OA\Property(property="id", type="integer", example="1"),
     *         @OA\Property(property="name", type="string", example="test album"),
     *         @OA\Property(property="group_id", type="integer", example="3"),
     *     ),
     *     @OA\Property(property="groupHistories", type="object", description="group_historiesテーブルとのリレーションデータ", required={"id", "group_id"},
     *         @OA\Property(property="id", type="integer", example="12"),
     *         @OA\Property(property="group_id", type="integer", example="3"),
     *     )
     * )
     */
    /**
     * @OA\Schema(
     *     schema="user_groups_info",
     *     required={"id", "name", "image_file", "private_flg"},
     *     @OA\Property(property="id", type="integer", example="10"),
     *     @OA\Property(property="name", type="string", example="test10"),
     *     @OA\Property(property="image_file", type="string", example="xxxxoooo.png"),
     *     @OA\Property(property="private_flg", type="integer", example="0"),
     * )
     */


    /**
     * @OA\Get(
     *     path="/api/users",
     *     description="statusがMEMBERもしくはADMINのユーザ情報をページネーション形式で取得する(件数：50件)",
     *     produces={"application/json"},
     *     tags={"users"},
     *     @OA\Parameter(
     *         name="name@like",
     *         description="ユーザ名の検索値(あいまい検索に対応)",
     *         in="query",
     *         required=false,
     *         type="string"
     *     ),
     *     @OA\Parameter(
     *         name="sort_name",
     *         description="ユーザ名でソート",
     *         in="query",
     *         required=false,
     *         type="string"
     *     ),
     *     @OA\Parameter(
     *         name="sort_created_at",
     *         description="作成日時順でソート",
     *         in="query",
     *         required=false,
     *         type="string"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success / statusがMEMBERもしくはADMINのユーザデータを表示",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 description="statusがMEMBERもしくはADMINのユーザデータを表示",
     *                 @OA\Items(
     *                      ref="#/components/schemas/user_list"
     *                 ),
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
     *                 example="ユーザ情報を取得出来ませんでした"
     *             )
     *         )
     *     ),
     * )
     * 
     * 【ハンバーガーメニュー】
     * ユーザ一覧の表示用アクション
     */
    public function index(Request $request)
    {
        try {
            // 検索条件
            $conditions = [];
            if($request->input('name@like') || $request->input('@instatus')) $conditions = Common::setConditions($request);
            
            // ソート条件
            $order = [];
            if($request->sort_name || $request->sort_created_at) $order = Common::setOrder($request);
    
            $data = $this->db->searchQueryPaginate($conditions, $order, 50);
            
            return response()->json(['users' => $data], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            Log::error(config('const.SystemMessage.SYSTEM_ERR').get_class($this).'::'.__FUNCTION__.":".$e->getMessage(). $this->getUserInfo($request));

            return response()->json([
              'error_message' => config('const.User.GET_ERR'),
              'status'        => 500,
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }
    

    /**
     * @OA\Get(
     *     path="/api/users/{user}",
     *     description="指定したユーザの情報をすべて取得する",
     *     produces={"application/json"},
     *     tags={"users"},
     *     @OA\Parameter(
     *         name="user",
     *         description="ユーザID",
     *         in="path",
     *         required=true,
     *         type="string"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success / 存在するユーザかつステータスがMEMBERもしくはADMINのユーザデータを表示",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 description="存在するユーザかつステータスがMEMBERもしくはADMINのユーザデータを表示",
     *                 ref="#/components/schemas/user_detail"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request error / 存在しないユーザのページをリクエストした場合、検索結果が0件であることを表すメッセージを表示",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="error_message",
     *                 type="string",
     *                 description="検索結果が0件であることを表すメッセージを表示",
     *                 example="指定したユーザは存在しません"
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
     *                 example="ユーザ情報を取得出来ませんでした"
     *             )
     *         )
     *     ),
     * )
     * 
     * 
     * ユーザ詳細の表示用アクション
     */
    public function show(Request $request, $user)
    {
        try {
            // ユーザ情報の取得
            // 検索条件の設定
            $conditions = [
                'id'        => $user
            ];
            
            $profile = $this->db->searchFirst($conditions);

            // ユーザが存在しない場合
            if(empty($profile)) {
                return response()->json(['error_message' => config('const.User.SEARCH_ERR')], 400, [], JSON_UNESCAPED_UNICODE);    
            }
            
            return response()->json([
                'user'      => $profile,
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            Log::error(config('const.SystemMessage.SYSTEM_ERR').get_class($this).'::'.__FUNCTION__.":".$e->getMessage(). $this->getUserInfo($request));

            return response()->json([
              'error_message' => config('const.User.GET_ERR'),
              'status'        => 500,
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * @OA\Get(
     *     path="api/users/{user}/edit",
     *     description="編集対象のユーザ情報を取得する",
     *     produces={"application/json"},
     *     tags={"users"},
     *     @OA\Parameter(
     *         name="user",
     *         description="ユーザID",
     *         in="path",
     *         required=true,
     *         type="string"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success / 編集するユーザのデータを表示(基本的にはログインしているユーザは自身の情報しか編集できないようにする)",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 description="編集するユーザのデータを表示(基本的にはログインしているユーザは自身の情報しか編集できないようにする)",
     *                 ref="#/components/schemas/user_detail"
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
     *                 example="ユーザ情報を取得出来ませんでした"
     *             )
     *         )
     *     ),
     * )
     * 
     * 
     * ユーザ情報の編集時表示用アクション
     */
    public function edit(Request $request, $user)
    {
        try {
            // ユーザ情報の取得
            // 検索条件の設定
            $conditions = [
                'id'        => $user
            ];
            
            $edituser = $this->db->getEditInfo($conditions);

            // ユーザが存在しない場合
            if(empty($edituser)) {
                throw new Exception('編集用のユーザ情報取得に失敗しました');   
            }
            
            return response()->json([
                'edituser'      => $edituser,
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            Log::error(config('const.SystemMessage.SYSTEM_ERR').get_class($this).'::'.__FUNCTION__.":".$e->getMessage(). $this->getUserInfo($request));

            return response()->json([
              'error_message' => config('const.User.GET_ERR'),
              'status'        => 500,
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/validate",
     *     description="ユーザ新規登録時のバリデーションを実行する",
     *     produces={"application/json"},
     *     tags={"users"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="request",
     *                 type="object",
     *                 description="リクエストボディのjsonのプロパティの例",
     *                 ref="#/components/schemas/user_register"
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success / バリデーションチェック通過のメッセージをリターン",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="validate_status",
     *                 type="string",
     *                 description="バリデーションチェック通過のメッセージをリターン",
     *                 example="OK"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request error / バリデーションエラーのメッセージを表示",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="errors",
     *                 type="string",
     *                 description="バリデーションエラーのメッセージを表示",
     *                 ref="#/components/schemas/user_errors"
     *             )
     *         )
     *     ),
     * )
     * 
     * ユーザバリデーション用メソッド(sanctumなし)
     *   ※データ登録時には非同期処理で常時確認に使用
     */
    public function webValidate(UserRegisterRequest $request)
    {
        return [
            'validate_status' => config('const.SystemMessage.VALIDATE_STATUS')
        ];
    }
    
    /**
     * @OA\Post(
     *     path="api/users/validate",
     *     description="ユーザ更新時のバリデーションを実行する",
     *     produces={"application/json"},
     *     tags={"users"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="request",
     *                 type="object",
     *                 description="リクエストボディのjsonのプロパティの例",
     *                 ref="#/components/schemas/user_update"
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success / バリデーションチェック通過のメッセージをリターン",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="validate_status",
     *                 type="string",
     *                 description="バリデーションチェック通過のメッセージをリターン",
     *                 example="OK"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request error / バリデーションエラーのメッセージを表示",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="errors",
     *                 type="string",
     *                 description="バリデーションエラーのメッセージを表示",
     *                 ref="#/components/schemas/user_errors"
     *             )
     *         )
     *     ),
     * )
     * 
     * ユーザバリデーション用メソッド(sanctumあり)
     *   ※データ登録時には非同期処理で常時確認に使用
     */
    public function userValidate(UserRegisterRequest $request)
    {
        return [
            'validate_status' => config('const.SystemMessage.VALIDATE_STATUS')
        ];
    }

    /**
     * @OA\Post(
     *     path="/api/users",
     *     description="ユーザデータを保存する",
     *     produces={"application/json"},
     *     tags={"users"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="request",
     *                 type="object",
     *                 description="リクエストボディのjsonのプロパティの例",
     *                 ref="#/components/schemas/user_register"
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success / 保存成功のメッセージを表示",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="info_message",
     *                 type="string",
     *                 description="保存成功のメッセージを表示",
     *                 example="ユーザ情報を登録しました"
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
     *                 example="ユーザ情報の登録に失敗しました"
     *             )
     *         )
     *     ),
     * )
     * 
     * ユーザ登録処理用アクション
     */
    public function store(UserRegisterRequest $request)
    {
        DB::beginTransaction();
        try {
            // ファイル名の生成
            $filename = null;
            if ($request->file('image_file')){
              $filename = Common::getFilename($request->file('image_file'));
            }
    
            $data = $request->all();
            // パスワードのハッシュ処理
            $data['password'] = Hash::make($data['password']);
            $data['image_file'] = $filename;
            $data['user_agent'] = $request->header('User-Agent');
            
            // データの保存処理
            $data = $this->db->save($data);

            // ファイルの保存処理
            if($request->file('image_file')) {
                Common::fileSave($request->file('image_file'), config('const.Aws.USER'), $data->id, $filename);
            }

            // 登録時の自動配信用ニュースを保存
            $this->db->saveWelcomeInfo($data->id);

            DB::commit();
            return response()->json([
                'info_message' => config('const.User.REGISTER_INFO'),
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            DB::rollback();
            Log::error(config('const.SystemMessage.SYSTEM_ERR').get_class($this).'::'.__FUNCTION__.":".$e->getMessage(). $this->getUserInfo($request));

            // 作成失敗時はエラーメッセージを返す
            return response()->json([
              'error_message' => config('const.User.REGISTER_ERR'),
              'status'        => 500,
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/users/{user}",
     *     description="ユーザデータを更新保存する",
     *     produces={"application/json"},
     *     tags={"users"},
     *     @OA\Parameter(
     *         name="user",
     *         description="ユーザID",
     *         in="path",
     *         required=true,
     *         type="string"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="request",
     *                 type="object",
     *                 description="リクエストボディのjsonのプロパティの例",
     *                 ref="#/components/schemas/user_update"
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success / 保存成功のメッセージを表示",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="info_message",
     *                 type="string",
     *                 description="保存成功のメッセージを表示",
     *                 example="ユーザ情報を登録しました"
     *             ),
     *             @OA\Property(
     *                 property="edituser",
     *                 type="string",
     *                 description="更新完了後のユーザデータ",
     *                 ref="#/components/schemas/user_update"
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
     *                 example="ユーザ情報の登録に失敗しました"
     *             )
     *         )
     *     ),
     * )
     * 
     * ユーザ更新処理用アクション
     */
    public function update(UserRegisterRequest $request)
    {
        DB::beginTransaction();
        try {

            $data = $request->all();
            
            // ファイル名の生成
            $filename = null;
            if ($request->file('image_file')){
                $filename = Common::getFilename($request->file('image_file'));
                $data['image_file'] = $filename;
            }
    
            // データの保存処理
            $data = $this->db->save($data);

            // ファイルの保存処理
            if($request->file('image_file')) {
                Common::fileSave($request->file('image_file'), config('const.Aws.USER'), $data->id, $filename);
            }

            // 検索条件の設定
            $conditions = [
                'id'        => $data->id
            ];
            
            $edituser = $this->db->getEditInfo($conditions);

            DB::commit();
            return response()->json([
                'info_message' => config('const.User.REGISTER_INFO'),
                'edituser'     => $edituser,
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            DB::rollback();
            Log::error(config('const.SystemMessage.SYSTEM_ERR').get_class($this).'::'.__FUNCTION__.":".$e->getMessage(). $this->getUserInfo($request));

            // 作成失敗時はエラーメッセージを返す
            return response()->json([
              'error_message' => config('const.User.REGISTER_ERR'),
              'status'        => 500,
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/users/{user}",
     *     description="ユーザデータを論理削除する",
     *     produces={"application/json"},
     *     tags={"users"},
     *     @OA\Parameter(
     *         name="user",
     *         description="ユーザID",
     *         in="path",
     *         required=true,
     *         type="string"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success / 論理削除成功のメッセージを表示",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="info_message",
     *                 type="string",
     *                 description="論理削除成功のメッセージを表示",
     *                 example="退会が完了しました"
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
     *                 example="サーバーエラーにより退会に失敗しました。管理者にお問い合わせください"
     *             )
     *         )
     *     ),
     * )
     * 
     * アカウント退会用アクション
     */
    public function destroy(Request $request, $user)
    {
        try {
            DB::beginTransaction();

            // データ削除
            $this->db->baseDelete($user);
            
            DB::commit();
            return response()->json(['info_message' => config('const.User.DELETE_INFO')], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            DB::rollback();
            Log::error(config('const.SystemMessage.SYSTEM_ERR').get_class($this).'::'.__FUNCTION__.":".$e->getMessage(). $this->getUserInfo($request));

            return response()->json([
              'error_message' => config('const.User.DELETE_ERR'),
              'status'        => 500,
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * @OA\Get(
     *     path="api/users/{user}/families",
     *     description="同じグループに加盟しているユーザ情報をすべて取得する",
     *     produces={"application/json"},
     *     tags={"users"},
     *     @OA\Parameter(
     *         name="user",
     *         description="ユーザID",
     *         in="path",
     *         required=true,
     *         type="string"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success / 同一グループにいる、group_historiesテーブルのstatusが2(メンバー)であるユーザの情報を表示",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 description="同一グループにいる、group_historiesテーブルのstatusが2(メンバー)であるユーザの情報を表示",
     *                 @OA\Items(
     *                      ref="#/components/schemas/user_list"
     *                 ),
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
     *                 example="ユーザ情報を取得出来ませんでした"
     *             )
     *         )
     *     ),
     * )
     * 
     * 
     * 同じグループに参加中のユーザ一覧
     */
    public function families(Request $request, $user)
    {
        try {
            // 検索条件
            $mygroup_conditions = [
                'user_id' => Auth::user()->id,
                'status'  => config('const.GroupHistory.APPROVAL')
            ];

            // 所属グループの取得
            $groups = $this->db->getGroups($mygroup_conditions);

            // 検索条件
            $families_conditions['@ingroup_id'] = Common::setInCondition($groups->toArray());
            $families_conditions['status'] = config('const.GroupHistory.APPROVAL');
            $families_conditions['@not_equaluser_id'] = Auth::user()->id;
            // ソート条件
            $order = [];
            if($request->sort_name || $request->sort_id) $order = Common::setOrder($request);
            
            // ファミリー情報取得
            $data = $this->db->getFamilies($families_conditions, $order, 30);
            
            return response()->json(['families' => $data], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            Log::error(config('const.SystemMessage.SYSTEM_ERR').get_class($this).'::'.__FUNCTION__.":".$e->getMessage(). $this->getUserInfo($request));

            return response()->json([
              'error_message' => config('const.User.GET_ERR'),
              'status'        => 500,
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * @OA\Get(
     *     path="api/users/{user}/groups",
     *     description="自身が加盟しているグループ情報をすべて取得する",
     *     produces={"application/json"},
     *     tags={"users"},
     *     @OA\Parameter(
     *         name="user",
     *         description="ユーザID",
     *         in="path",
     *         required=true,
     *         type="string"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success / ログインしているユーザの、group_historiesテーブルのstatusが2(メンバー)であるグループ情報を表示",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 description="ログインしているユーザの、group_historiesテーブルのstatusが2(メンバー)であるグループ情報を表示",
     *                 @OA\Items(
     *                      ref="#/components/schemas/user_groups"
     *                 ),
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
     *                 example="ユーザ情報を取得出来ませんでした"
     *             )
     *         )
     *     ),
     * )
     * 
     * 
     * 自身が参加しているグループの一覧
     */
    public function participating(Request $request, $user)
    {
        try {
            // 検索条件
            $mygroup_conditions = [
                'user_id' => Auth::user()->id,
                'status'  => config('const.GroupHistory.APPROVAL')
            ];

            // 所属グループの取得
            $groups = $this->db->getGroups($mygroup_conditions);

            // 検索条件
            $group_conditions['@ingroups.id'] = Common::setInCondition($groups->toArray());
            // ソート条件
            $order = [];
            if($request->sort_name || $request->sort_id) $order = Common::setOrder($request);
            
            // 参加中グループ情報取得
            $data = $this->db->getParticipating($group_conditions, $order);
            
            return response()->json(['participants' => $data], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            Log::error(config('const.SystemMessage.SYSTEM_ERR').get_class($this).'::'.__FUNCTION__.":".$e->getMessage(). $this->getUserInfo($request));

            return response()->json([
              'error_message' => config('const.User.GET_ERR'),
              'status'        => 500,
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * @OA\Get(
     *     path="api/users/{user}/messagelists",
     *     description="自身がトークでメッセージのやり取りをしているすべてのユーザの情報と、それらのユーザに紐づく最新メッセージをすべて取得する",
     *     produces={"application/json"},
     *     tags={"users"},
     *     @OA\Parameter(
     *         name="user",
     *         description="ユーザID",
     *         in="path",
     *         required=true,
     *         type="string"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success / メッセージのやり取りをしているすべてのユーザの情報と、それらのユーザに紐づく最新メッセージを表示",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 description="メッセージのやり取りをしているすべてのユーザの情報と、それらのユーザに紐づく最新メッセージを表示",
     *                 require={"id", "content", "own_id", "user_id", "update_user_id", "created_at", "updated_at", "otherid", "mcount", "other"},
     *                 @OA\Items(
     *                      @OA\Property(property="id", type="integer", example=24),
     *                      @OA\Property(property="content", type="string", example="元気にしてたー？"),
     *                      @OA\Property(property="own_id", type="integer", example=2),
     *                      @OA\Property(property="user_id", type="integer", example=4),
     *                      @OA\Property(property="update_user_id", type="integer", example=2),
     *                      @OA\Property(property="created_at", type="string", example="2021-04-25 12:02:55"),
     *                      @OA\Property(property="updated_at", type="string", example="2021-04-28 14:13:00"),
     *                      @OA\Property(property="otherid", type="integer", example=4),
     *                      @OA\Property(property="mcount", type="integer", example=3),
     *                      @OA\Property(property="other", type="object", description="usersテーブルとのリレーションデータ", required={"id", "name", "image_file"},
     *                          @OA\Property(property="id", type="integer", example=6),
     *                          @OA\Property(property="name", type="string", example="test user"),
     *                          @OA\Property(property="image_file", type="string", example="xxxxoooo.png"),
     *                      )
     *                 )
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
     *                 example="ユーザ情報を取得出来ませんでした"
     *             )
     *         )
     *     ),
     * )
     * 
     * 
     * トークリストの一覧
     */
    public function messages(Request $request, $user)
    {
        try {
            // トーク取得
            $data = $this->db->getMessageList(Auth::user()->id);
            
            return response()->json(['talklist' => $data], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            Log::error(config('const.SystemMessage.SYSTEM_ERR').get_class($this).'::'.__FUNCTION__.":".$e->getMessage(). $this->getUserInfo($request));

            return response()->json([
              'error_message' => config('const.User.GET_ERR'),
              'status'        => 500,
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * @OA\Get(
     *     path="api/users/{user}/wgroups",
     *     description="自身が作成したグループで、「歓迎中」として、すべてのユーザに公開するデータを取得する",
     *     produces={"application/json"},
     *     tags={"users"},
     *     @OA\Parameter(
     *         name="user",
     *         description="ユーザID",
     *         in="path",
     *         required=true,
     *         type="string"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success / ログインしているユーザが作成した、groupsテーブルのwelcome_flgが1(歓迎中)であるグループ情報を表示",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 description="ログインしているユーザが作成した、groupsテーブルのwelcome_flgが1(歓迎中)であるグループ情報を表示",
     *                 @OA\Items(
     *                      ref="#/components/schemas/user_groups"
     *                 ),
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
     *                 example="ユーザ情報を取得出来ませんでした"
     *             )
     *         )
     *     ),
     * )
     * 
     * 
     * 歓迎中のグループ一覧(UserDetail用)
     */
    public function welcomeGgroups(Request $request, $user)
    {
        try {
            // 検索条件
            $conditions['groups.host_user_id'] = $user;
            $conditions['groups.welcome_flg']  = config('const.Group.WELCOME');
            $conditions['groups.private_flg'] = config('const.Group.PUBLIC');
            // ソート条件
            $order = [
                'created_at' => 'desc'
            ];
            
            // 参加歓迎中グループ情報取得
            $wgroups = $this->db->getParticipating($conditions, $order);
            
            return response()->json(['wgroups' => $wgroups], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            Log::error(config('const.SystemMessage.SYSTEM_ERR').get_class($this).'::'.__FUNCTION__.":".$e->getMessage(). $this->getUserInfo($request));

            return response()->json([
              'error_message' => config('const.Group.GET_ERR'),
              'status'        => 500,
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * @OA\Get(
     *     path="api/users/{user}/pgroups",
     *     description="ユーザの詳細ページにて、そのユーザ参加していて、かつ公開設定が「公開」となっているグループ情報を取得する",
     *     produces={"application/json"},
     *     tags={"users"},
     *     @OA\Parameter(
     *         name="user",
     *         description="ユーザID",
     *         in="path",
     *         required=true,
     *         type="string"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success / アクセスしたユーザ詳細ページに紹介されているユーザが、参加しているグループ情報をgroupsテーブルのprivate_flgが0(公開)である情報に限定して表示",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 description="アクセスしたユーザ詳細ページに紹介されているユーザが、参加しているグループ情報をgroupsテーブルのprivate_flgが0(公開)である情報に限定して表示",
     *                 @OA\Items(
     *                      ref="#/components/schemas/user_groups_info"
     *                 ),
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
     *                 example="グループ情報を取得出来ませんでした"
     *             )
     *         )
     *     ),
     * )
     * 
     * 
     * 参加中のグループ一覧(UserDetail用)
     */
    public function participatingGroups(Request $request, $user)
    {
        try {
            // 検索条件
            $mygroup_conditions = [
                'user_id' => $user,
                'status'  => config('const.GroupHistory.APPROVAL')
            ];
            
            // 所属グループの取得
            $groups = $this->db->getGroups($mygroup_conditions);
            // 検索条件
            $conditions = [];
            $conditions['@ingroups.id']       = Common::setInCondition($groups->toArray());
            $conditions['groups.private_flg'] = config('const.Group.PUBLIC');
            // ソート条件
            $order = [
                'created_at' => 'desc'
            ];

            // 参加中グループ情報取得
            $pgroups = $this->db->getGroupsInfo($conditions, $order);
            
            return response()->json(['pgroups' => $pgroups], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            Log::error(config('const.SystemMessage.SYSTEM_ERR').get_class($this).'::'.__FUNCTION__.":".$e->getMessage(). $this->getUserInfo($request));

            return response()->json([
              'error_message' => config('const.Group.GET_ERR'),
              'status'        => 500,
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * @OA\Get(
     *     path="api/users/{user}/igroups",
     *     description="自身が作成したグループに、別ユーザを招待するためのデータを取得する",
     *     produces={"application/json"},
     *     tags={"users"},
     *     @OA\Parameter(
     *         name="user",
     *         description="ユーザID",
     *         in="path",
     *         required=true,
     *         type="string"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success / ログインしているユーザが作成した、groupsテーブルのhost_user_idが自身のIDであるグループ情報を表示。また、すでに招待するユーザが参加しているグループ情報は省いて表示する",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 description="
     *                      ログインしているユーザが作成した、groupsテーブルのhost_user_idが自身のIDであるグループ情報を表示。
     *                      また、すでに招待するユーザが参加しているグループ情報は省いて表示する
     *                 ",
     *                 @OA\Items(
     *                      ref="#/components/schemas/user_groups_info"
     *                 ),
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
     *                 example="グループ情報を取得出来ませんでした"
     *             )
     *         )
     *     ),
     * )
     * 
     * 
     * 招待用のグループ一覧(UserDetail用)
     */
    public function inviteGgroups(Request $request, $user)
    {
        try {
            // 検索条件
            $mygroup_conditions = [
                'user_id' => $user,
                'status'  => config('const.GroupHistory.APPROVAL')
            ];
            
            // 招待ユーザの所属グループの取得
            $groups = $this->db->getGroups($mygroup_conditions);

            // 検索条件
            $conditions['groups.host_user_id'] = Auth::user()->id;
            $conditions['@not_ingroups.id']    = Common::setInCondition($groups->toArray());
            // ソート条件
            $order = [
                'created_at' => 'desc'
            ];

            // 参加中グループ情報取得
            $igroups = $this->db->getGroupsInfo($conditions, $order);
            
            return response()->json(['igroups' => $igroups], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            Log::error(config('const.SystemMessage.SYSTEM_ERR').get_class($this).'::'.__FUNCTION__.":".$e->getMessage(). $this->getUserInfo($request));

            return response()->json([
              'error_message' => config('const.Group.GET_ERR'),
              'status'        => 500,
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }
}
