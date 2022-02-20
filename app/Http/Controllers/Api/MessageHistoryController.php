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
     * @OA\Schema(
     *     schema="message_histories_list",
     *     required={"id", "content", "own_id", "user_id", "update_user_id", "created_at", "updated_at", "deleted_at", "own"},
     *     @OA\Property(property="id", type="integer", example=1),
     *     @OA\Property(property="content", type="string", example="初メッセージです！"),
     *     @OA\Property(property="own_id", type="integer", example=1),
     *     @OA\Property(property="user_id", type="integer", example="2"),
     *     @OA\Property(property="update_user_id", type="integer", example="1"),
     *     @OA\Property(property="created_at", type="string", example="2021-04-25 12:02:55"),
     *     @OA\Property(property="updated_at", type="string", example="2021-04-28 14:13:00"),
     *     @OA\Property(property="deleted_at", type="string", example="null"),
     *     @OA\Property(property="own", type="object", required={"id", "name", "image_file"},
     *          @OA\Property(property="id", type="integer", example=2),
     *          @OA\Property(property="name", type="string", example="test2"),
     *          @OA\Property(property="image_file", type="string", example="xxxxoooo.jpg"),
     *     ),
     * )
     */
    /**
     * @OA\Schema(
     *     schema="message_histories_register",
     *     required={"content", "own_id", "user_id"},
     *     @OA\Property(property="content", type="string", example="メッセージを初めて投稿します"),
     *     @OA\Property(property="own_id", type="integer", example=2),
     *     @OA\Property(property="user_id", type="integer", example=1),
     * )
     */

     /**
     * @OA\Get(
     *     path="api/users/{user}/messages",
     *     description="クエリストリングによりリクエストしたパラメータと一致するトーク情報を20件取得する",
     *     produces={"application/json"},
     *     tags={"message_histories"},
     *     @OA\Parameter(
     *         name="user",
     *         description="自身のユーザIDを設定",
     *         in="path",
     *         required=true,
     *         type="string"
     *     ),
     *     @OA\Parameter(
     *         name="user_id",
     *         description="指定した相手側のユーザIDのデータを検索する",
     *         in="query",
     *         required=true,
     *         type="string"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success / 指定したユーザとのトークデータを表示",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="talks",
     *                 type="array",
     *                 description="指定したユーザとのトークデータを表示",
     *                 @OA\Items(
     *                      ref="#/components/schemas/message_histories_list"
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
     *                 example="メッセージ履歴を取得出来ませんでした"
     *             )
     *         )
     *     ),
     * )
     * 
     * 特定ユーザとのメッセージ一覧
     */
    public function index(Request $request, $user)
    {
        try {
            if(!$request->input('user_id')) throw new Exception('requestの値にuser_idが設定されていません。');
            // 検索条件
            $conditions = [];
            $conditions['own_id']  = $user;
            $conditions['user_id'] = $request->input('user_id');
            
            // ソート条件
            $order = [];
    
            // データ
            $data = $this->db->getMessages($conditions);

            return response()->json(['talks' => $data], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            Log::error(config('const.SystemMessage.SYSTEM_ERR').get_class($this).'::'.__FUNCTION__.":".$e->getMessage(). $this->getUserInfo($request));

            return response()->json([
              'error_message' => config('const.Message.GET_ERR'),
              'status'        => 500,
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * @OA\Post(
     *     path="api/users/{user}/messages",
     *     description="メッセージデータを保存する",
     *     produces={"application/json"},
     *     tags={"message_histories"},
     *     @OA\Parameter(
     *         name="user",
     *         description="自身のユーザIDを設定",
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
     *                 ref="#/components/schemas/message_histories_register"
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success / 保存したメッセージを返す",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="talk",
     *                 type="object",
     *                 description="保存したメッセージを返す",
     *                 ref="#/components/schemas/message_histories_list"
     *             ),
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
     *                 example="メッセージの送信に失敗しました"
     *             )
     *         )
     *     ),
     * )
     * 
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

            // 検索条件
            $conditions = [];
            $conditions['id']  = $data->id;
    
            // データ
            $talk = $this->db->getMessage($conditions);

            // Pusherにデータを送信(リアルタイム通信を実行)
            event(new MessageCreated($talk));

            DB::commit();
            return response()->json(['talk' => $talk], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            DB::rollback();
            Log::error(config('const.SystemMessage.SYSTEM_ERR').get_class($this).'::'.__FUNCTION__.":".$e->getMessage(). $this->getUserInfo($request));

            // 作成失敗時はエラーメッセージを返す
            return response()->json([
              'error_message' => config('const.Message.REGISTER_ERR'),
              'status'        => 500,
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * @OA\Delete(
     *     path="api/users/{user}/messages/{message}",
     *     description="メッセージデータを論理削除する",
     *     produces={"application/json"},
     *     tags={"message_histories"},
     *     @OA\Parameter(
     *         name="user",
     *         description="ユーザID(自身) ※パラメータ値として活用することはない",
     *         in="path",
     *         required=true,
     *         type="string"
     *     ),
     *     @OA\Parameter(
     *         name="message",
     *         description="メッセージID ※削除データの検索条件として活用する",
     *         in="path",
     *         required=true,
     *         type="string"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success / 論理削除成功のメッセージを表示",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="info_message",
     *                 type="string",
     *                 description="論理削除成功のメッセージを表示",
     *                 example="メッセージの削除が完了しました"
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
     *                 example="サーバーエラーによりメッセージの削除に失敗しました。管理者にお問い合わせください"
     *             )
     *         )
     *     ),
     * )
     * 
     * メッセージの削除用アクション
     */
    public function destroy(Request $request, $user, $message)
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
            Log::error(config('const.SystemMessage.SYSTEM_ERR').get_class($this).'::'.__FUNCTION__.":".$e->getMessage(). $this->getUserInfo($request));

            return response()->json([
              'error_message' => config('const.Message.DELETE_ERR'),
              'status'        => 500,
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }
}
