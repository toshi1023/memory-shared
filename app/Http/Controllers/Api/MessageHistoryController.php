<?php

namespace App\Http\Controllers\Api;

use App\Events\MessageCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\MessageHistoryRequest;
use Illuminate\Http\Request;
use App\Lib\Common;
use App\Repositories\MessageHistory\MessageHistoryRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class MessageHistoryController extends Controller
{
    protected $db;

    public function __construct(MessageHistoryRepositoryInterface $database)
    {
        $this->db = $database;
    }

    /**
     * 特定ユーザとのメッセージ一覧
     */
    public function index(Request $request, $user)
    {
        try {
            // 検索条件
            $conditions = [];
            $conditions['own_id']  = Auth::user()->id;
            $conditions['user_id'] = $user;
            
            // ソート条件
            $order = [];
    
            // データ
            $data = $this->db->getMessages($conditions);

            return response()->json(['talks' => $data], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            Log::error(config('const.SystemMessage.SYSTEM_ERR').get_class($this).'::'.__FUNCTION__.":".$e->getMessage());

            return response()->json([
              'error_message' => config('const.Message.GET_ERR'),
              'status'        => 500,
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * メッセージの保存処理用アクション
     */
    public function store(MessageHistoryRequest $request)
    {   
        try {
            DB::beginTransaction();

            // メッセージの保存
            $data = $this->db->save($request->all());

            // 未読管理テーブルに保存
            $this->db->saveMread($data);
    
            // Pusherにデータを送信(リアルタイム通信を実行)
            event(new MessageCreated($data));

            DB::commit();
            return response()->json($data, 200, [], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            DB::rollback();
            Log::error(config('const.SystemMessage.SYSTEM_ERR').get_class($this).'::'.__FUNCTION__.":".$e->getMessage());

            // 作成失敗時はエラーメッセージを返す
            return response()->json([
              'error_message' => config('const.Message.REGISTER_ERR'),
              'status'        => 500,
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * メッセージの削除用アクション
     */
    public function destroy(Request $request, $message)
    {
        try {
            DB::beginTransaction();

            // ログインユーザのIDが削除対象メッセージのown_idと一致しない場合はエラーを返す
            if($this->db->baseSearchFirst(['id' => $message])->own_id !== Auth::user()->id) {
                throw new Exception(config('const.Message.NOT_OWN_ID').'[ユーザID: '.Auth::user()->id.', トークID: '.$message.']');
            }
            
            // データ削除
            $this->db->delete($message, Auth::user()->id);
            
            DB::commit();
            return response()->json(['info_message' => config('const.Message.DELETE_INFO')], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            DB::rollback();
            Log::error(config('const.SystemMessage.SYSTEM_ERR').get_class($this).'::'.__FUNCTION__.":".$e->getMessage());

            return response()->json([
              'error_message' => config('const.Message.DELETE_ERR'),
              'status'        => 500,
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }
}
