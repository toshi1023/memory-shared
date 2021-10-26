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
     * ニュース一覧画面の申請中グループ情報を取得
     */
    public function index(Request $request)
    {
        try {
            // 検索条件
            $conditions = [];
            $conditions['group_histories.user_id'] = Auth::user()->id;
            if($request->input('@not_equalstatus')) $conditions['@not_equalgroup_histories.status'] = $request->input('@not_equalstatus');
            if($request->input('@date')) {
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
    
            // データの保存処理
            $data = $this->db->save($data);

            // 申請したグループ情報を取得
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
            // 承認の場合
            if((int)$data['status'] === config('const.GroupHistory.APPROVAL')) {
                return response()->json([
                    'info_message' => config('const.GroupHistory.APPROVAL_INFO'),
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
                    'error_message' => config('const.GroupHistory.APPROVAL_ERR'),
                    'status'        => 500,
                ], 500, [], JSON_UNESCAPED_UNICODE);
            }
        }
    }

    /**
     * グループ履歴登録処理用アクション
     *   ※$request: 'status'のみ
     */
    public function update(Request $request, $group, $history)
    {
        DB::beginTransaction();
        try {
            // データの配列化
            $data = $request->all();
            $data['id'] = $history;
    
            // データの保存処理
            $this->db->save($data);

            // グループ情報を取得
            $groupInfo = $this->db->searchGroupFirst(['id' => $group]);

            // 申請状況のデータが承認済みの場合、familiesテーブルへの保存処理を実行
            if((int)$data['status'] === config('const.GroupHistory.APPROVAL')) {
                // ニュースデータの作成と未読管理データの作成
                $this->db->saveGroupInfo($data['user_id'], $groupInfo->name, config('const.GroupHistory.APPROVAL'));

                CreateFamily::dispatch($group, $data['user_id']);
            }

            DB::commit();

            // 招待した場合
            if($request->input('invite_flg')) {
                return response()->json([
                    'info_message' => config('const.GroupHistory.INVITE_INFO'),
                    'group'        => $groupInfo,
                ], 200, [], JSON_UNESCAPED_UNICODE);
            }
            // 承認の場合
            if((int)$data['status'] === config('const.GroupHistory.APPROVAL')) {
                return response()->json([
                    'info_message' => config('const.GroupHistory.APPROVAL_INFO'),
                    'group'        => $groupInfo,
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
            if((int)$data['status'] === config('const.GroupHistory.APPROVAL')) {
                return response()->json([
                    'error_message' => config('const.GroupHistory.APPROVAL_ERR'),
                    'status'        => 500,
                ], 500, [], JSON_UNESCAPED_UNICODE);
            }
            // 拒否の場合
            if((int)$data['status'] === config('const.GroupHistory.REJECT')) {
                return response()->json([
                    'error_message' => config('const.GroupHistory.REJECT_ERR'),
                    'status'        => 500,
                ], 500, [], JSON_UNESCAPED_UNICODE);
            }
        }
    }
}
