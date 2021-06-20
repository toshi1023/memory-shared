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
     *     required={"id", "name", "email", "status", "image_file"},
     *     @OA\Property(property="id", type="integer", example=1),
     *     @OA\Property(property="name", type="string", example="test1"),
     *     @OA\Property(property="email", type="string", example="test1@xxx.co.jp"),
     *     @OA\Property(property="status", type="integer", example="1"),
     *     @OA\Property(property="image_file", type="string", example="xxxxoooo.png"),
     * )
     */
    /**
     * @OA\Schema(
     *     schema="user_register",
     *     required={"id", "name", "email", "password", "status", "user_agent", "image_file"},
     *     @OA\Property(property="id", type="integer", example=1),
     *     @OA\Property(property="name", type="string", example="test1"),
     *     @OA\Property(property="password", type="string", example="test1234"),
     *     @OA\Property(property="email", type="string", example="test1@xxx.co.jp"),
     *     @OA\Property(property="status", type="integer", example="1"),
     *     @OA\Property(property="user_agent", type="string", example="Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_8_8) AppleWebKit/5330 (KHTML, like Gecko) Chrome/36.0.833.0 Mobile Safari/5330"),
     *     @OA\Property(property="image_file", type="string", example="xxxxoooo.png"),
     * )
     */


    /**
     * @OA\Get(
     *     path="/api/users",
     *     description="statusがMEMBERのユーザ情報をすべて取得する",
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
     *         name="email",
     *         description="メールアドレスの検索値(あいまい検索に対応)",
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
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 description="statusがMEMBERのユーザデータを返す",
     *                 @OA\Items(
     *                      ref="#/components/schemas/user_list"
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
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
            $conditions['status'] = config('const.User.MEMBER');
            if($request->input('email@like') || $request->input('name@like')) $conditions = Common::setConditions($request);
            
            // ソート条件
            $order = [];
            if($request->sort_name || $request->sort_id) $order = Common::setOrder($request);
    
            $data = $this->db->searchQuery($conditions, $order);
            
            return response()->json($data, 200, [], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            Log::error(config('const.SystemMessage.SYSTEM_ERR').get_class($this).'::'.__FUNCTION__.":".$e->getMessage());

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
     *         description="ユーザ名",
     *         in="path",
     *         required=true,
     *         type="string"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 description="存在するユーザかつステータスがMEMBERのユーザデータ",
     *                 ref="#/components/schemas/user_list"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="存在しないユーザのページをリクエストした場合",
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
     *         description="Server error",
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
     * ユーザ詳細の表示用アクション
     *   ※$user: nameカラムの値を設定する
     */
    public function show(Request $request, $user)
    {
        try {
            // 検索条件の設定
            $conditions = [
                'name'      => $user,
                'status'    => config('const.User.MEMBER')
            ];
            
            $data = $this->db->searchFirst($conditions);

            // ユーザが存在しない場合
            if(empty($data)) {
                return response()->json(['error_message' => config('const.User.SEARCH_ERR')], 404, [], JSON_UNESCAPED_UNICODE);    
            }
            
            return response()->json($data, 200, [], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            Log::error(config('const.SystemMessage.SYSTEM_ERR').get_class($this).'::'.__FUNCTION__.":".$e->getMessage());

            return response()->json([
              'error_message' => config('const.User.GET_ERR'),
              'status'        => 500,
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * ユーザバリデーション用メソッド
     *   ※データ登録時には非同期処理で常時確認に使用
     */
    public function userValidate(UserRegisterRequest $request)
    {
        return;
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
     *         description="Success",
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
     *         description="Server error",
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
            $data['image_file'] = $filename;
    
            // データの保存処理
            $this->db->save($data);

            // ファイルの保存処理
            if($request->file('image_file')) {
                Common::fileSave($request->file('image_file'), config('const.Aws.USER'), $request->name, $filename);
            }

            DB::commit();
            return response()->json([
                'info_message' => config('const.User.REGISTER_INFO'),
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            DB::rollback();
            Log::error(config('const.SystemMessage.SYSTEM_ERR').get_class($this).'::'.__FUNCTION__.":".$e->getMessage());

            // 作成失敗時はエラーメッセージを返す
            return response()->json([
              'error_message' => config('const.User.REGISTER_ERR'),
              'status'        => 500,
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
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
            $this->db->save($data);

            // ファイルの保存処理
            if($request->file('image_file')) {
                Common::fileSave($request->file('image_file'), config('const.Aws.USER'), $request->name, $filename);
            }

            DB::commit();
            return response()->json([
                'info_message' => config('const.User.REGISTER_INFO'),
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            DB::rollback();
            Log::error(config('const.SystemMessage.SYSTEM_ERR').get_class($this).'::'.__FUNCTION__.":".$e->getMessage());

            // 作成失敗時はエラーメッセージを返す
            return response()->json([
              'error_message' => config('const.User.REGISTER_ERR'),
              'status'        => 500,
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * アカウント退会用アクション
     */
    public function destroy(Request $request, $user)
    {
        try {
            DB::beginTransaction();

            // 検索条件の設定
            $conditions = [
                'name'      => $user
            ];
            
            $data = $this->db->searchFirst($conditions);

            // データ削除
            $this->db->baseDelete($data->id);
            
            DB::commit();
            return response()->json(['info_message' => config('const.User.DELETE_INFO')], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            DB::rollback();
            Log::error(config('const.SystemMessage.SYSTEM_ERR').get_class($this).'::'.__FUNCTION__.":".$e->getMessage());

            return response()->json([
              'error_message' => config('const.User.DELETE_ERR'),
              'status'        => 500,
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * 【navメニュー】
     * 同じグループに参加中のユーザ一覧
     */
    public function families(Request $request, $user)
    {
        try {
            // テスト用
            $user = $this->db->searchFirst(['name' => $user]);
            
            // 検索条件
            $mygroup_conditions = [
                // 'user_id' => Auth::user()->id,  // 本番用
                'user_id' => $user->id,            // テスト用
                'status'  => config('const.GroupHistory.APPROVAL')
            ];

            // 所属グループの取得
            $groups = $this->db->getGroups($mygroup_conditions);

            // 検索条件
            $families_conditions['@ingroup_id'] = Common::setInCondition($groups->toArray());
            // ソート条件
            $order = [];
            if($request->sort_name || $request->sort_id) $order = Common::setOrder($request);

            // ファミリー情報取得
            $data = $this->db->getFamilies($families_conditions, $order);
            
            return response()->json($data, 200, [], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            Log::error(config('const.SystemMessage.SYSTEM_ERR').get_class($this).'::'.__FUNCTION__.":".$e->getMessage());

            return response()->json([
              'error_message' => config('const.User.GET_ERR'),
              'status'        => 500,
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * 【navメニュー】
     * 参加グループの一覧
     */
    public function participating(Request $request, $user)
    {
        try {
            // テスト用
            $user = $this->db->searchFirst(['name' => $user]);

            // 検索条件
            $mygroup_conditions = [
                // 'user_id' => Auth::user()->id,  // 本番用
                'user_id' => $user->id,            // テスト用
                'status'  => config('const.GroupHistory.APPROVAL')
            ];

            // 所属グループの取得
            $groups = $this->db->getGroups($mygroup_conditions);

            // 検索条件
            $group_conditions['@ingroup_id'] = Common::setInCondition($groups->toArray());
            // ソート条件
            $order = [];
            if($request->sort_name || $request->sort_id) $order = Common::setOrder($request);

            // 参加中グループ情報取得
            $data = $this->db->getParticipating($group_conditions, $order);

            // 参加中グループの人数を取得
            
            return response()->json($data, 200, [], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            Log::error(config('const.SystemMessage.SYSTEM_ERR').get_class($this).'::'.__FUNCTION__.":".$e->getMessage());

            return response()->json([
              'error_message' => config('const.User.GET_ERR'),
              'status'        => 500,
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }
}
