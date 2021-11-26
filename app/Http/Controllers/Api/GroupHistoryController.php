<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\GroupHistory\GroupHistoryRepositoryInterface;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Lib\Common;
use App\Jobs\CreateFamily;
use Carbon\Carbon;

class GroupHistoryController extends Controller
{
    protected $db;

    public function __construct(GroupHistoryRepositoryInterface $database)
    {
        $this->db = $database;
    }

    /**
     * @OA\Schema(
     *     schema="group_histories_list",
     *     required={"id", "user_id", "group_id", "status", "memo", "update_user_id", "created_at", "updated_at", "deleted_at", "group"},
     *     @OA\Property(property="id", type="integer", example=1),
     *     @OA\Property(property="user_id", type="integer", example=3),
     *     @OA\Property(property="group_id", type="integer", example="4"),
     *     @OA\Property(property="status", type="integer", example="2", description="1: 申請中, 2: 承認済み, 3: 却下"),
     *     @OA\Property(property="memo", type="string", example="ここに備考を記載（フロント画面では使用しない）"),
     *     @OA\Property(property="update_user_id", type="integer", example="6", description="statusが 1 のときはuser_idと同値, statusが 2 もしくは 3 のときはgroup_idに一致するgroupsテーブルのhost_user_idと同値"),
     *     @OA\Property(property="created_at", type="string", example="2021-04-25 12:02:55"),
     *     @OA\Property(property="updated_at", type="string", example="2021-04-28 14:13:00"),
     *     @OA\Property(property="deleted_at", type="string", example="null"),
     *     @OA\Property(property="group", type="object", required={"id", "name", "image_file"},
     *          @OA\Property(property="id", type="integer", example=4),
     *          @OA\Property(property="name", type="string", example="test group 4"),
     *          @OA\Property(property="image_file", type="string", example="xxxxoooo.jpg"),
     *     ),
     * )
     */
    /**
     * @OA\Schema(
     *     schema="group_histories_users_list",
     *     required={"id", "user_id", "group_id", "status", "memo", "update_user_id", "created_at", "updated_at", "deleted_at", "user"},
     *     @OA\Property(property="id", type="integer", example=1),
     *     @OA\Property(property="user_id", type="integer", example=3),
     *     @OA\Property(property="group_id", type="integer", example="4"),
     *     @OA\Property(property="status", type="integer", example="1", description="1: 申請中, 2: 承認済み, 3: 却下"),
     *     @OA\Property(property="memo", type="string", example="ここに備考を記載（フロント画面では使用しない）"),
     *     @OA\Property(property="update_user_id", type="integer", example="3", description="statusが 1 のときはuser_idと同値, statusが 2 もしくは 3 のときはgroup_idに一致するgroupsテーブルのhost_user_idと同値"),
     *     @OA\Property(property="created_at", type="string", example="2021-04-25 12:02:55"),
     *     @OA\Property(property="updated_at", type="string", example="2021-04-28 14:13:00"),
     *     @OA\Property(property="deleted_at", type="string", example="null"),
     *     @OA\Property(property="group", type="object", required={"id", "name", "image_file"},
     *          @OA\Property(property="id", type="integer", example=3),
     *          @OA\Property(property="name", type="string", example="test user 3"),
     *          @OA\Property(property="image_file", type="string", example="xxxxoooo.jpg"),
     *     ),
     * )
     */
    /**
     * @OA\Schema(
     *     schema="group_histories_register",
     *     required={"user_id", "status"},
     *     @OA\Property(property="user_id", type="integer", example=9),
     *     @OA\Property(property="status", type="integer", example="1"),
     * )
     */
    /**
     * @OA\Schema(
     *     schema="group_histories_update",
     *     required={"id", "user_id", "status"},
     *     @OA\Property(property="id", type="integer", example=24),
     *     @OA\Property(property="user_id", type="integer", example=9),
     *     @OA\Property(property="status", type="integer", example="2"),
     * )
     */

     /**
     * @OA\Get(
     *     path="api/history",
     *     description="クエリストリングによりリクエストしたパラメータと一致するグループ情報、もしくはユーザ情報を全件取得する",
     *     produces={"application/json"},
     *     tags={"group_histories"},
     *     @OA\Parameter(
     *         name="@not_equalstatus",
     *         description="指定したステータスではないデータを検索",
     *         in="query",
     *         required=false,
     *         type="string"
     *     ),
     *     @OA\Parameter(
     *         name="@datecreated_at",
     *         description="指定した日付までを遡ったデータを検索",
     *         in="query",
     *         required=false,
     *         type="string"
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         description="指定したステータスであるデータを検索",
     *         in="query",
     *         required=false,
     *         type="string"
     *     ),
     *     @OA\Parameter(
     *         name="group_id",
     *         description="指定したグループでデータを検索",
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
     *         description="Success / 指定したグループもしくはグループに参加するユーザのデータを表示",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="group_histories",
     *                 type="array",
     *                 description="指定したグループのデータを表示",
     *                 @OA\Items(
     *                      ref="#/components/schemas/group_histories_list"
     *                 ),
     *             ),
     *             @OA\Property(
     *                 property="ghusers",
     *                 type="array",
     *                 description="指定したグループに参加するユーザのデータを表示",
     *                 @OA\Items(
     *                      ref="#/components/schemas/group_histories_users_list"
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
     * ニュース一覧画面の申請中グループ情報を取得
     */
    public function index(Request $request)
    {
        try {
            // 検索条件
            $conditions = [];
            $conditions['group_histories.user_id'] = Auth::user()->id;
            if($request->input('@not_equalstatus')) $conditions['@not_equalgroup_histories.status'] = $request->input('@not_equalstatus');
            if($request->input('@datecreated_at')) {
                // 指定した日付までを遡ったデータを取得するように条件設定
                $days = Carbon::today()->subDay((int)$request->input('@datecreated_at'));
                $conditions['@dategroup_histories.created_at'] = $days;
            }
            // ソート条件
            $order = [];

            $data = $this->db->searchQuery($conditions, $order);

            // ユーザ情報のみ結合して取得する場合
            $users = null;
            if($request->input('status')) {
                // 検索条件
                $conditions = [];
                $conditions['group_histories.user_id'] = Auth::user()->id;
                if($request->input('group_id') || $request->input('status')) $conditions = Common::setConditions($request);

                // ソート条件
                $order = [];
                if($request->input('sort_created_at')) $order = Common::setOrder($request);

                $users = $this->db->searchQueryUsers($conditions, $order);
            }
            
            return response()->json([
                'group_histories' => $data,
                'ghusers'         => $users,
            ], 200, [], JSON_UNESCAPED_UNICODE);
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
     *     path="api/groups/{group}/history",
     *     description="グループ申請状況のデータを保存する",
     *     produces={"application/json"},
     *     tags={"group_histories"},
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
     *                 ref="#/components/schemas/group_histories_register"
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success / 保存成功のメッセージを表示",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="info_message ※申請時",
     *                 type="string",
     *                 description="保存成功のメッセージを表示",
     *                 example="グループに参加を申請しました"
     *             ),
     *             @OA\Property(
     *                 property="info_message ※招待時",
     *                 type="string",
     *                 description="保存成功のメッセージを表示",
     *                 example="グループに招待しました"
     *             ),
     *             @OA\Property(
     *                 property="group",
     *                 type="object",
     *                 description="リクエストボディのjsonのプロパティの例",
     *                 ref="#/components/schemas/group_detail"
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error / サーバエラー用のメッセージを表示",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="error_message ※申請時",
     *                 type="string",
     *                 description="サーバエラー用のメッセージを表示",
     *                 example="グループの参加申請に失敗しました"
     *             ),
     *             @OA\Property(
     *                 property="error_message ※招待時",
     *                 type="string",
     *                 description="サーバエラー用のメッセージを表示",
     *                 example="グループの招待に失敗しました"
     *             )
     *         )
     *     ),
     * )
     * 
     * グループ履歴登録処理用アクション
     *   ※$request: 'status'のみ
     */
    public function store(Request $request, $group)
    {
        DB::beginTransaction();
        try {
            // データの配列化
            $data = $request->all();
            $data['group_id'] = $group;
            $data['user_id'] = $request->input('user_id') ? $request->input('user_id') : Auth::user()->id;

            // 検索条件作成
            $conditions = [
                'group_id'  => $data['group_id'],
                'user_id'   => $data['user_id']
            ];
            // 既存データが存在しないかどうか確認
            if($this->db->searchExists($conditions)) {
                throw new Exception('すでに履歴データが存在するユーザ・グループ間のデータ保存処理が実行されようとしました。 グループID：'.$data['group_id'].', ユーザID：'.$data['user_id']);
            }
            
            // データの保存処理
            $data = $this->db->save($data);
            
            // 招待したグループ情報を取得
            $groupInfo = $this->db->searchGroupFirst(['id' => $group]);

            // 申請状況のデータが承認済みの場合、familiesテーブルへの保存処理を実行
            if((int)$data['status'] === config('const.GroupHistory.APPROVAL')) {
                // ニュースデータの作成と未読管理データの作成
                $this->db->saveGroupInfo($data['user_id'], $groupInfo->name, config('const.GroupHistory.APPROVAL'));

                CreateFamily::dispatch($group, $data['user_id']);
            } else {
                // ニュースデータの作成と未読管理データの作成
                $this->db->saveGroupInfo($data['user_id'], $groupInfo->name, config('const.GroupHistory.APPLY'));
            }

            // 申請状況のデータが申請中の場合
            if((int)$data['status'] === config('const.GroupHistory.APPLY')) {
                $groupInfo = $this->db->searchGroupDetailFirst(['id' => $group]);
            }

            DB::commit();
            
            // 申請の場合
            if((int)$data['status'] === config('const.GroupHistory.APPLY')) {
                return response()->json([
                    'info_message' => config('const.GroupHistory.APPLY_INFO'),
                    'group'        => $groupInfo,
                ], 200, [], JSON_UNESCAPED_UNICODE);
            }
            // 承認の場合(招待したときのみ実行)
            if((int)$data['status'] === config('const.GroupHistory.APPROVAL')) {
                return response()->json([
                    'info_message' => config('const.GroupHistory.INVITE_INFO'),
                    'group'        => $groupInfo,
                ], 200, [], JSON_UNESCAPED_UNICODE);
            }
        } catch (Exception $e) {
            DB::rollback();
            Log::error(config('const.SystemMessage.SYSTEM_ERR').get_class($this).'::'.__FUNCTION__.":".$e->getMessage());

            // 申請の場合
            if((int)$data['status'] === config('const.GroupHistory.APPLY')) {
                return response()->json([
                    'error_message' => config('const.GroupHistory.APPLY_ERR'),
                    'status'        => 500,
                ], 500, [], JSON_UNESCAPED_UNICODE);
            }
            // 承認の場合
            if((int)$data['status'] === config('const.GroupHistory.APPROVAL')) {
                return response()->json([
                    'error_message' => config('const.GroupHistory.INVITE_ERR'),
                    'status'        => 500,
                ], 500, [], JSON_UNESCAPED_UNICODE);
            }
        }
    }

    /**
     * @OA\Put(
     *     path="api/groups/{group}/history/{history}",
     *     description="グループ申請状況のデータを更新する",
     *     produces={"application/json"},
     *     tags={"group_histories"},
     *     @OA\Parameter(
     *         name="group",
     *         description="グループID",
     *         in="path",
     *         required=true,
     *         type="string"
     *     ),
     *     @OA\Parameter(
     *         name="history",
     *         description="グループ履歴ID",
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
     *                 ref="#/components/schemas/group_histories_update"
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success / 保存成功のメッセージを表示",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="info_message ※承認時",
     *                 type="string",
     *                 description="保存成功のメッセージを表示",
     *                 example="グループの参加を承認しました"
     *             ),
     *             @OA\Property(
     *                 property="info_message ※却下時",
     *                 type="string",
     *                 description="保存成功のメッセージを表示",
     *                 example="グループに参加を拒否しました"
     *             ),
     *             @OA\Property(
     *                 property="pusers",
     *                 type="array",
     *                 description="参加が承認された場合は参加中ユーザのデータを表示",
     *                 @OA\Items(
     *                    @OA\Property(property="id", type="integer", example=5),
     *                    @OA\Property(property="name", type="string", example="test user 5"),
     *                    @OA\Property(property="image_file", type="string", example="xxxxoooo.jpg"),
     *                 )
     *             ),
     *             @OA\Property(
     *                 property="ghusers",
     *                 type="array",
     *                 description="参加申請中ユーザのデータを表示",
     *                 @OA\Items(
     *                      ref="#/components/schemas/group_histories_users_list"
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
     *                 property="error_message ※承認時",
     *                 type="string",
     *                 description="サーバエラー用のメッセージを表示",
     *                 example="グループの参加承認に失敗しました"
     *             ),
     *             @OA\Property(
     *                 property="error_message ※却下時",
     *                 type="string",
     *                 description="サーバエラー用のメッセージを表示",
     *                 example="グループの参加拒否に失敗しました"
     *             )
     *         )
     *     ),
     * )
     * 
     * グループ履歴登録処理用アクション
     *   ※$request: 'status'のみ
     */
    public function update(Request $request, $group, $history)
    {
        DB::beginTransaction();
        try {
            // データの配列化
            $data = $request->all();

            // グループのホストであるかどうかを確認
            if(!$this->db->confirmGroupHost(Auth::user()->id, $group)) {
                throw new Exception('ホストでないユーザがグループの承認作業を実行されようとしました。 グループID：'.$group.', ユーザID：'.Auth::user()->id);
            }
            
            // データの保存処理
            $this->db->save($data);

            // グループ情報を取得
            $groupInfo = $this->db->searchGroupFirst(['id' => $group]);

            $pusers = null;
            $ghusers = null;

            // 申請状況のデータが承認済みの場合、familiesテーブルへの保存処理を実行
            if((int)$data['status'] === config('const.GroupHistory.APPROVAL')) {
                // ニュースデータの作成と未読管理データの作成
                $this->db->saveGroupInfo($data['user_id'], $groupInfo->name, config('const.GroupHistory.APPROVAL'));

                CreateFamily::dispatch($group, $data['user_id']);

                // 検索条件
                $conditions = [
                    'group_histories.group_id' => $group,
                    'group_histories.status'   => config('const.GroupHistory.APPROVAL')
                ];
                $users = $this->db->getFamilies($conditions);
                $conditions = [];
                $conditions['@inusers.id'] = Common::setInCondition($users->toArray());
                // 参加中ユーザ
                $pusers = $this->db->getUsersInfo($conditions);
            }

            // 却下の場合論理削除を実行
            if((int)$data['status'] === config('const.GroupHistory.REJECT')) {
                $this->db->baseDelete($history);
            }

            // 検索条件
            $conditions = [];
            $conditions['group_histories.group_id'] = $group;
            $conditions['group_histories.status'] = config('const.GroupHistory.APPLY');

            // ソート条件
            $order = [];
            if($request->input('sort_created_at')) $order = Common::setOrder($request);
            // 参加申請中ユーザ
            $ghusers = $this->db->searchQueryUsers($conditions, $order);

            DB::commit();

            // 承認の場合
            if((int)$data['status'] === config('const.GroupHistory.APPROVAL')) {
                return response()->json([
                    'info_message' => config('const.GroupHistory.APPROVAL_INFO'),
                    'pusers'       => $pusers,
                    'ghusers'      => $ghusers
                ], 200, [], JSON_UNESCAPED_UNICODE);
            }
            // 拒否の場合
            if((int)$data['status'] === config('const.GroupHistory.REJECT')) {
                return response()->json([
                    'info_message' => config('const.GroupHistory.REJECT_INFO'),
                ], 200, [], JSON_UNESCAPED_UNICODE);
            }
        } catch (Exception $e) {
            DB::rollback();
            Log::error(config('const.SystemMessage.SYSTEM_ERR').get_class($this).'::'.__FUNCTION__.":".$e->getMessage());

            // 承認の場合
            if((int)$request->input('status') === config('const.GroupHistory.APPROVAL')) {
                return response()->json([
                    'error_message' => config('const.GroupHistory.APPROVAL_ERR'),
                    'status'        => 500,
                ], 500, [], JSON_UNESCAPED_UNICODE);
            }
            // 拒否の場合
            if((int)$request->input('status') === config('const.GroupHistory.REJECT')) {
                return response()->json([
                    'error_message' => config('const.GroupHistory.REJECT_ERR'),
                    'status'        => 500,
                ], 500, [], JSON_UNESCAPED_UNICODE);
            }
        }
    }
}
