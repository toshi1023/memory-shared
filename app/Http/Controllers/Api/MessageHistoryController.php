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
    public function index(Request $request)
    {
        try {
            // 検索条件
            $conditions = [];
            if($request->input('user_id')) {
                $conditions = Common::setConditions($request);
            }
            
            // ソート条件
            $order = [];
    
            $data = $this->db->baseSearchQueryPaginate($conditions, $order, 20);
            
            return response()->json($data, 200, [], JSON_UNESCAPED_UNICODE);
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
    
            // Pusherにデータを送信(リアルタイム通信を実行)
            event(new MessageCreated($data));

            DB::commit();
            return response()->json($data, 200, [], JSON_UNESCAPED_UNICODE);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error(config('const.SystemMessage.SYSTEM_ERR').get_class($this).'::'.__FUNCTION__.":".$e->getMessage());

            // 作成失敗時はエラーメッセージを返す
            return response()->json([
              'error_message' => config('const.Message.REGISTER_ERR'),
              'status'        => 500,
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }
}
