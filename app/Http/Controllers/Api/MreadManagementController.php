<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\MreadManagement\MreadManagementRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class MreadManagementController extends Controller
{
    protected $db;

    public function __construct(MreadManagementRepositoryInterface $database)
    {
        $this->db = $database;
    }

    /**
     * 未読の削除用アクション
     */
    public function destroy(Request $request, $user)
    {
        try {
            DB::beginTransaction();

            // 検索条件
            $conditions = [
                'own_id'  => $user, 
                'user_id' => Auth::user()->id
            ];
            // $conditions = [
            //     'own_id'  => $user, 
            //     'user_id' => 1
            // ];

            $messages = $this->db->searchQuery($conditions);
            
            // データ削除
            $this->db->delete($conditions, $messages);
            
            DB::commit();
            return response()->json([], 200, [], JSON_UNESCAPED_UNICODE);
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
