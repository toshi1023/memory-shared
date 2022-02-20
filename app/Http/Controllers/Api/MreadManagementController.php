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
     * @OA\Delete(
     *     path="api/users/{user}/mread",
     *     description="メッセージの未読データを物理削除する",
     *     produces={"application/json"},
     *     tags={"read_managements"},
     *     @OA\Parameter(
     *         name="user",
     *         description="ユーザID(自身)",
     *         in="path",
     *         required=true,
     *         type="string"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success / 何も返さない",
     *         @OA\JsonContent(
     *             type="object",
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
     *                 example="予期しないエラーが発生しました。管理者にお問い合わせください"
     *             )
     *         )
     *     ),
     * )
     * 
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

            $messages = $this->db->searchQuery($conditions);
            
            // データ削除
            $this->db->delete($conditions, $messages);
            
            DB::commit();
            return response()->json([], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            DB::rollback();
            $this->getErrorLog($request, $e, get_class($this), __FUNCTION__);

            return response()->json([
              'error_message' => config('const.SystemMessage.UNEXPECTED_ERR'),
              'status'        => 500,
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }
}
