<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\NreadManagement\NreadManagementRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class NreadManagementController extends Controller
{
    protected $db;

    public function __construct(NreadManagementRepositoryInterface $database)
    {
        $this->db = $database;
    }

    /**
     * 未読の削除用アクション
     */
    public function destroy(Request $request, $news)
    {
        try {
            DB::beginTransaction();
            
            // データ削除
            $key = [
                'news_user_id' => $request->input('news_user_id'), 
                'news_id' => $news, 
                'user_id' => $request->input('user_id')
            ];
            $this->db->delete($key);

            // 未読フラグ削除後のニュースデータを取得
            // 検索条件
            $conditions = [
                'news.user_id' => $request->input('news_user_id'), 
                'news.news_id' => $news
            ];

            $data = $this->db->getNewsFirst($conditions);
            
            DB::commit();
            return response()->json(['news' => $data], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            DB::rollback();
            Log::error(config('const.SystemMessage.SYSTEM_ERR').get_class($this).'::'.__FUNCTION__.":".$e->getMessage());

            return response()->json([
              'error_message' => config('const.SystemMessage.UNEXPECTED_ERR'),
              'status'        => 500,
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }
}
