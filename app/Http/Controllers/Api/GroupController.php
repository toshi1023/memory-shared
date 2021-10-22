<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Group\GroupRepositoryInterface;
use App\Http\Requests\GroupRegisterRequest;
use App\Jobs\DeleteFamily;
use App\Lib\Common;
use App\Models\GroupHistory;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class GroupController extends Controller
{
    protected $db;

    public function __construct(GroupRepositoryInterface $database)
    {
        $this->db = $database;
    }

    /**
     * @OA\Schema(
     *     schema="group_list",
     *     required={"id", "name", "description", "private_flg", "welcome_flg", "image_file", "host_user_id", "created_at", "updated_at", "users"},
     *     @OA\Property(property="id", type="integer", example=1),
     *     @OA\Property(property="name", type="string", example="test group 1"),
     *     @OA\Property(property="description", type="string", example="盛り上げていきましょう！"),
     *     @OA\Property(property="private_flg", type="integer", example="0"),
     *     @OA\Property(property="welcome_flg", type="integer", example="1"),
     *     @OA\Property(property="image_file", type="string", example="xxxxoooo.png"),
     *     @OA\Property(property="host_user_id", type="number", example="1"),
     *     @OA\Property(property="created_at", type="string", example="2021-04-25 12:02:55"),
     *     @OA\Property(property="updated_at", type="string", example="2021-04-28 14:13:00"),
     *     @OA\Property(property="users", type="object", description="usersテーブルとのリレーションデータ", required={"id", "name", "image_file", "gender"},
     *         @OA\Property(property="id", type="integer", example="7"),
     *         @OA\Property(property="name", type="string", example="test user"),
     *         @OA\Property(property="image_file", type="string", example="xxxxoooo.png"),
     *         @OA\Property(property="gender", type="integer", example="1"),
     *     )
     * )
     */
    /**
     * @OA\Schema(
     *     schema="group_detail",
     *     required={"id", "name", "description", "private_flg", "welcome_flg", "image_file", "host_user_id", "created_at", "updated_at", "users"},
     *     @OA\Property(property="id", type="integer", example=2),
     *     @OA\Property(property="name", type="string", example="test group 2"),
     *     @OA\Property(property="description", type="string", example="バスケを楽しむグループです"),
     *     @OA\Property(property="private_flg", type="integer", example="0"),
     *     @OA\Property(property="welcome_flg", type="integer", example="1"),
     *     @OA\Property(property="image_file", type="string", example="xxxxoooo.png"),
     *     @OA\Property(property="host_user_id", type="number", example="1"),
     *     @OA\Property(property="created_at", type="string", example="2021-04-25 12:02:55"),
     *     @OA\Property(property="updated_at", type="string", example="2021-04-28 14:13:00"),
     *     @OA\Property(property="users", type="object", description="usersテーブルとのリレーションデータ", required={"id", "name", "image_file", "gender"},
     *         @OA\Property(property="id", type="integer", example="9"),
     *         @OA\Property(property="name", type="string", example="test user 9"),
     *         @OA\Property(property="image_file", type="string", example="xxxxoooo.png"),
     *         @OA\Property(property="gender", type="integer", example="0"),
     *     ),
     *     @OA\Property(property="groupHistories", type="object", description="group_historiesテーブルとのリレーションデータ", required={"id", "group_id", "user_id", "status"},
     *         @OA\Property(property="id", type="integer", example="11"),
     *         @OA\Property(property="group_id", type="integer", example="2"),
     *         @OA\Property(property="user_id", type="integer", example="4"),
     *         @OA\Property(property="gender", type="integer", example="2"),
     *     )
     * )
     */
    /**
     * @OA\Schema(
     *     schema="group_register",
     *     required={"name", "description", "private_flg", "welcome_flg", "image_file", "host_user_id"},
     *     @OA\Property(property="name", type="string", example="test group 4"),
     *     @OA\Property(property="description", type="string", example="ヒッチハイクを楽しむために作りました！"),
     *     @OA\Property(property="private_flg", type="integer", example="0"),
     *     @OA\Property(property="welcome_flg", type="integer", example="1"),
     *     @OA\Property(property="image_file", type="string", example="xxxxoooo.png"),
     *     @OA\Property(property="host_user_id", type="number", example="6")
     * )
     */
    /**
     * @OA\Schema(
     *     schema="group_update",
     *     required={"id", "name", "description", "private_flg", "welcome_flg", "image_file", "host_user_id"},
     *     @OA\Property(property="id", type="integer", example=2),
     *     @OA\Property(property="name", type="string", example="test group 4"),
     *     @OA\Property(property="description", type="string", example="ヒッチハイクを楽しむために作りました！"),
     *     @OA\Property(property="private_flg", type="integer", example="0"),
     *     @OA\Property(property="welcome_flg", type="integer", example="0"),
     *     @OA\Property(property="image_file", type="string", example="xxxxoooo.png"),
     *     @OA\Property(property="host_user_id", type="number", example="6")
     * )
     */
    /**
     * @OA\Schema(
     *     schema="group_errors",
     *     required={"name", "image_file", "host_user_id"},
     *     @OA\Property(property="name", type="object", required={"name.max", "GroupNameRule"},
     *          @OA\Property(property="name.max", type="string", example="グループ名は50文字以内で入力してください"),
     *          @OA\Property(property="GroupNameRule", type="string", description="独自ルールを作成。private_flgが0(公開)の場合のみ、グループ名の重複を許さない", example="公開する場合には重複したグループ名を使用できません。非公開にするか、グループ名を変更してください"),
     *     ),
     *     @OA\Property(property="image_file", type="object", required={"mimes", "image_file.max"},
     *          @OA\Property(property="mimes", type="string", example="アップロードファイルはjpeg,png,jpg,gifタイプのみ有効です"),
     *          @OA\Property(property="image_file.max", type="string", example="1Mを超えています。"),
     *     ),
     *     @OA\Property(property="host_user_id", type="object", required={"GroupUpdateRule"},
     *          @OA\Property(property="GroupUpdateRule", type="string", description="独自ルールを作成。グループの作成者以外、グループデータの更新を許さない", example="グループ作成者以外はグループ情報を更新できません")
     *     ),
     * )
     */
    /**
     * @OA\Schema(
     *     schema="group_users_info",
     *     required={"id", "name", "image_file"},
     *     @OA\Property(property="id", type="integer", example="8"),
     *     @OA\Property(property="name", type="string", example="test group 8"),
     *     @OA\Property(property="image_file", type="string", example="xxxxoooo.png")
     * )
     */

     /**
     * @OA\Get(
     *     path="/api/groups",
     *     description="private_flgが0(公開)のグループデータをページネーション形式で取得する(件数：50件)",
     *     produces={"application/json"},
     *     tags={"groups"},
     *     @OA\Parameter(
     *         name="name@like",
     *         description="グループ名の検索値(あいまい検索に対応)",
     *         in="query",
     *         required=false,
     *         type="string"
     *     ),
     *     @OA\Parameter(
     *         name="sort_name",
     *         description="グループ名でソート",
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
     *         description="Success / private_flgが0(公開)のグループデータを表示",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 description="private_flgが0(公開)のグループデータを表示",
     *                 @OA\Items(
     *                      ref="#/components/schemas/group_list"
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
     * グループ一覧の表示用アクション
     */
    public function index(Request $request)
    {
        try {
            // 検索条件
            $conditions = [];
            $conditions['private_flg'] = config('const.Group.PUBLIC');
            if($request->input('name@like')) $conditions = Common::setConditions($request);
            
            // ソート条件
            $order = [];
            if($request->sort_name || $request->sort_created_at) $order = Common::setOrder($request);
    
            $data = $this->db->searchQueryPaginate($conditions, $order, 50);
            
            return response()->json(['groups' => $data], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            Log::error(config('const.SystemMessage.SYSTEM_ERR').get_class($this).'::'.__FUNCTION__.":".$e->getMessage());

            return response()->json([
              'error_message' => config('const.Group.GET_ERR'),
              'status'        => 500,
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/groups/{group}",
     *     description="指定したグループの情報をすべて取得する",
     *     produces={"application/json"},
     *     tags={"groups"},
     *     @OA\Parameter(
     *         name="group",
     *         description="グループID",
     *         in="path",
     *         required=true,
     *         type="string"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success / 指定したグループのデータを表示",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 description="指定したグループのデータを表示",
     *                 ref="#/components/schemas/group_detail"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="存在しないグループのページをリクエストした場合、検索結果が0件であることを表すメッセージを表示",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="error_message",
     *                 type="string",
     *                 description="検索結果が0件であることを表すメッセージを表示",
     *                 example="指定したグループは存在しません"
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
     * グループ詳細の表示用アクション
     *   ※$group: nameカラムの値を設定する
     */
    public function show(Request $request, $group)
    {
        try {
            $data = $this->db->searchFirst(['id' => $group]);

            // グループが存在しない場合
            if(empty($data)) {
                return response()->json(['error_message' => config('const.Group.SEARCH_ERR')], 404, [], JSON_UNESCAPED_UNICODE);    
            }
            
            return response()->json(['group' => $data], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            Log::error(config('const.SystemMessage.SYSTEM_ERR').get_class($this).'::'.__FUNCTION__.":".$e->getMessage());

            return response()->json([
              'error_message' => config('const.Group.GET_ERR'),
              'status'        => 500,
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * @OA\Post(
     *     path="api/groups/validate",
     *     description="グループ作成時もしくは更新時のバリデーションを実行する",
     *     produces={"application/json"},
     *     tags={"groups"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="request",
     *                 type="object",
     *                 description="リクエストボディのjsonのプロパティの例",
     *                 ref="#/components/schemas/group_update"
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
     *         description="Server error / バリデーションエラーのメッセージを表示",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="errors",
     *                 type="string",
     *                 description="バリデーションエラーのメッセージを表示",
     *                 ref="#/components/schemas/group_errors"
     *             )
     *         )
     *     ),
     * )
     * 
     * グループバリデーション用メソッド
     *   ※データ登録時には非同期処理で常時確認に使用
     */
    public function groupValidate(GroupRegisterRequest $request)
    {
        return [
            'validate_status' => config('const.SystemMessage.VALIDATE_STATUS')
        ];
    }

    /**
     * @OA\Post(
     *     path="/api/groups",
     *     description="グループデータを保存する",
     *     produces={"application/json"},
     *     tags={"groups"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="request",
     *                 type="object",
     *                 description="リクエストボディのjsonのプロパティの例",
     *                 ref="#/components/schemas/group_register"
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
     *                 example="グループ情報を登録しました"
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
     *                 example="グループ情報の登録に失敗しました"
     *             )
     *         )
     *     ),
     * )
     * 
     * グループ登録処理用アクション
     */
    public function store(GroupRegisterRequest $request)
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
            $group = $this->db->save($data);

            // ファイルの保存処理
            if($request->file('image_file')) {
                Common::fileSave($request->file('image_file'), config('const.Aws.Group'), $group->id, $filename);
            }

            // グループ履歴の作成
            $historyData = [
                'group_id'  => $group->id,
                'user_id'   => $group->host_user_id,
                'status'    => config('const.GroupHistory.APPROVAL')
            ];

            // グループ履歴の保存
            $this->db->save($historyData, GroupHistory::class);

            DB::commit();
            return response()->json([
                'info_message' => config('const.Group.REGISTER_INFO'),
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            DB::rollback();
            Log::error(config('const.SystemMessage.SYSTEM_ERR').get_class($this).'::'.__FUNCTION__.":".$e->getMessage());

            // 作成失敗時はエラーメッセージを返す
            return response()->json([
              'error_message' => config('const.Group.REGISTER_ERR'),
              'status'        => 500,
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/groups/{group}",
     *     description="グループデータを更新保存する",
     *     produces={"application/json"},
     *     tags={"groups"},
     *     @OA\Parameter(
     *         name="group",
     *         description="グループID",
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
     *                 ref="#/components/schemas/group_update"
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
     *                 example="グループ情報を登録しました"
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="string",
     *                 description="更新完了後のユーザデータ",
     *                 ref="#/components/schemas/group_update"
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
     *                 example="グループ情報の登録に失敗しました"
     *             )
     *         )
     *     ),
     * )
     * 
     * グループ更新処理用アクション
     */
    public function update(GroupRegisterRequest $request)
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
                Common::fileSave($request->file('image_file'), config('const.Aws.Group'), $data->id, $filename);
            }

            DB::commit();
            return response()->json([
                'info_message' => config('const.Group.REGISTER_INFO'),
                'data'         => $data,
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            DB::rollback();
            Log::error(config('const.SystemMessage.SYSTEM_ERR').get_class($this).'::'.__FUNCTION__.":".$e->getMessage());

            // 作成失敗時はエラーメッセージを返す
            return response()->json([
              'error_message' => config('const.Group.REGISTER_ERR'),
              'status'        => 500,
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/groups/{group}",
     *     description="グループデータを論理削除する",
     *     produces={"application/json"},
     *     tags={"groups"},
     *     @OA\Parameter(
     *         name="group",
     *         description="グループID",
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
     *                 example="グループの削除が完了しました"
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
     *                 example="サーバーエラーによりグループの削除に失敗しました。管理者にお問い合わせください"
     *             )
     *         )
     *     ),
     * )
     * 
     * グループの削除用アクション
     */
    public function destroy(Request $request, $group)
    {
        try {
            DB::beginTransaction();

            // host_user_idとログインユーザのIDが一致しない場合はエラーを返す
            if($this->db->baseSearchFirst(['id' => $group])->host_user_id !== Auth::user()->id) {
                throw new Exception(config('const.Group.NOT_HOST_ERR'));
            }
            
            // データ削除
            $this->db->delete($group);

            // familiesテーブルの削除処理を実行
            DeleteFamily::dispatch($group);
            
            DB::commit();
            return response()->json(['info_message' => config('const.Group.DELETE_INFO')], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            DB::rollback();
            Log::error(config('const.SystemMessage.SYSTEM_ERR').get_class($this).'::'.__FUNCTION__.":".$e->getMessage());

            return response()->json([
              'error_message' => config('const.Group.DELETE_ERR'),
              'status'        => 500,
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * @OA\Get(
     *     path="api/groups/{group}/users",
     *     description="指定したグループに参加しているユーザ情報をすべて取得する",
     *     produces={"application/json"},
     *     tags={"groups"},
     *     @OA\Parameter(
     *         name="group",
     *         description="グループID",
     *         in="path",
     *         required=true,
     *         type="string"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success / グループ一覧画面からアクセスしたグループ詳細ページに、そのグループに参加しているユーザ情報をusersテーブルから取得して表示",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="pusers",
     *                 type="array",
     *                 description="グループ一覧画面からアクセスしたグループ詳細ページに、そのグループに参加しているユーザ情報をusersテーブルから取得して表示",
     *                 @OA\Items(
     *                      ref="#/components/schemas/group_users_info"
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
     * 参加者一覧(GroupDetail用)
     */
    public function participating(Request $request, $group)
    {
        try {
            // 検索条件
            $group_conditions = [
                'group_histories.group_id' => $group,
                'group_histories.status'   => config('const.GroupHistory.APPROVAL')
            ];
            
            // 参加者の取得
            $users = $this->db->getParticipants($group_conditions);
            // 検索条件
            $conditions = [];
            $conditions['@inusers.id'] = Common::setInCondition($users->toArray());;
            // ソート条件
            $order = [
                'created_at' => 'desc'
            ];

            // 参加者情報取得
            $users = $this->db->getUsersInfo($conditions, $order);
            
            return response()->json(['pusers' => $users], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            Log::error(config('const.SystemMessage.SYSTEM_ERR').get_class($this).'::'.__FUNCTION__.":".$e->getMessage());

            return response()->json([
              'error_message' => config('const.User.GET_ERR'),
              'status'        => 500,
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }
}
