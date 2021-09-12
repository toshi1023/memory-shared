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
     * 【ハンバーガーメニュー】
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
            if($request->sort_name || $request->sort_id) $order = Common::setOrder($request);
    
            $data = $this->db->searchQueryPaginate($conditions, $order);
            
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
     * 【ハンバーガーメニュー】
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
            
            return response()->json($data, 200, [], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            Log::error(config('const.SystemMessage.SYSTEM_ERR').get_class($this).'::'.__FUNCTION__.":".$e->getMessage());

            return response()->json([
              'error_message' => config('const.Group.GET_ERR'),
              'status'        => 500,
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * グループバリデーション用メソッド
     *   ※データ登録時には非同期処理で常時確認に使用
     */
    public function groupValidate(GroupRegisterRequest $request)
    {
        return;
    }

    /**
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
}
