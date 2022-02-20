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
     * @OA\Get(
     *     path="api/nread",
     *     description="ログインユーザのニュース未読件数を取得する",
     *     produces={"application/json"},
     *     tags={"read_managements"},
     *     @OA\Response(
     *         response=200,
     *         description="Success / ログインユーザのニュース未読件数を表示",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="nread_count",
     *                 type="integer",
     *                 description="ログインユーザのニュース未読件数を表示",
     *                 example=5
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
     *                 example="予期しないエラーが発生しました。管理者にお問い合わせください"
     *             )
     *         )
     *     ),
     * )
     * 
     * ニュースの未読数を取得
     */
    public function count(Request $request)
    {
        try {
            // 検索条件
            $conditions = [];
            $conditions['@innews_user_id'] = [Auth::user()->id, 0];  // ログインユーザ向け & 全体向け
            $conditions['user_id'] = Auth::user()->id;
            // 件数取得
            $data = $this->db->searchQueryCount($conditions);
            
            return response()->json(['nread_count' => $data], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            Log::error(config('const.SystemMessage.SYSTEM_ERR').get_class($this).'::'.__FUNCTION__.":".$e->getMessage(). $this->getUserInfo($request));

            return response()->json([
              'error_message' => config('const.SystemMessage.UNEXPECTED_ERR'),
              'status'        => 500,
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * @OA\Delete(
     *     path="api/news/{news}/nread",
     *     description="ニュースの未読データを物理削除する",
     *     produces={"application/json"},
     *     tags={"read_managements"},
     *     @OA\Parameter(
     *         name="news",
     *         description="ニュースID",
     *         in="path",
     *         required=true,
     *         type="string"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success / 未読データを削除後、未読削除したニュースのデータを表示",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="news",
     *                 type="object",
     *                 description="未読削除したニュースのデータを表示",
     *                 ref="#/components/schemas/news_list"
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
     *                 example="予期しないエラーが発生しました。管理者にお問い合わせください"
     *             )
     *         )
     *     ),
     * )
     * 
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
            Log::error(config('const.SystemMessage.SYSTEM_ERR').get_class($this).'::'.__FUNCTION__.":".$e->getMessage(). $this->getUserInfo($request));

            return response()->json([
              'error_message' => config('const.SystemMessage.UNEXPECTED_ERR'),
              'status'        => 500,
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }
}
