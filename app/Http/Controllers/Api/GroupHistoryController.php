<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\GroupHistory\GroupHistoryRepositoryInterface;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Jobs\CreateFamily;

class GroupHistoryController extends Controller
{
    protected $db;

    public function __construct(GroupHistoryRepositoryInterface $database)
    {
        $this->db = $database;
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

            DB::commit();
            
            // 申請の場合
            if((int)$data['status'] === config('const.GroupHistory.APPLY')) {
                return response()->json([
                    'info_message' => config('const.GroupHistory.APPLY_INFO'),
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
    public function update(Request $request, $group)
    {
        DB::beginTransaction();
        try {
            // 検索条件
            $conditions = [];
            $conditions['group_id'] = $group;
            $conditions['user_id'] = $request->input('user_id') ? $request->input('user_id') : Auth::user()->id;

            // group_historiesのidを取得
            $id = $this->db->baseSearchFirst($conditions)->id;
            // データの配列化
            $data = $request->all();
            $data['id'] = $id;
    
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
