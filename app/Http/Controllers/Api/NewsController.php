<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\News\NewsRepositoryInterface;
use App\Http\Requests\NewsRegisterRequest;
use App\Lib\Common;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class NewsController extends Controller
{
    protected $db;

    public function __construct(NewsRepositoryInterface $database)
    {
        $this->db = $database;
    }

    /**
     * @OA\Schema(
     *     schema="news_list",
     *     required={"user_id", "news_id", "title", "content", "update_user_id", "created_at", "updated_at", "read_user_id"},
     *     @OA\Property(property="user_id", type="integer", example=2),
     *     @OA\Property(property="news_id", type="integer", example="1"),
     *     @OA\Property(property="title", type="string", example="MemoryShareAppへようこそ"),
     *     @OA\Property(property="content", type="string", example="たくさん思い出を共有してください"),
     *     @OA\Property(property="update_user_id", type="integer", example="2"),
     *     @OA\Property(property="created_at", type="string", example="2021-07-25 12:02:55"),
     *     @OA\Property(property="updated_at", type="string", example="2021-08-28 14:13:00"),
     *     @OA\Property(property="read_user_id", type="integer", example=2),
     * )
     */
    /**
     * @OA\Schema(
     *     schema="news_errors",
     *     required={"title", "content", "update_user_id", "onetime_password"},
     *     @OA\Property(property="title", type="object", required={"name.max"},
     *          @OA\Property(property="name.max", type="string", example="タイトルは100文字以内で入力してください"),
     *     ),
     *     @OA\Property(property="content", type="object", required={"required"},
     *          @OA\Property(property="required", type="string", example="内容は必須です"),
     *     ),
     *     @OA\Property(property="update_user_id", type="object", required={"NewsRegisterRule"},
     *          @OA\Property(property="NewsRegisterRule", type="string", description="ログインユーザに管理者権限があるかどうかを確認", example="ニュースを作成するには管理者権限が必要です"),
     *     ),
     *     @OA\Property(property="onetime_password", type="object", required={"required", "ConfirmOnetimePasswordRule"},
     *          @OA\Property(property="required", type="string", example="ワンタイムパスワードは必須です"),
     *          @OA\Property(property="ConfirmOnetimePasswordRule", type="string", description="管理者権限が必要な処理をする際に、入力されたワンタイムパスワードが一致するか確認", example="ワンタイムパスワードが一致しません"),
     *     ),
     * )
     */
    /**
     * @OA\Schema(
     *     schema="news_register",
     *     required={"user_id", "title", "content"},
     *     @OA\Property(property="user_id", type="integer", example=5),
     *     @OA\Property(property="title", type="string", example="不具合を解消しました"),
     *     @OA\Property(property="content", type="string", example="ログインが出来ない不具合を解消しました")
     * )
     */
    /**
     * @OA\Schema(
     *     schema="news_update",
     *     required={"user_id", "news_id", "title", "content"},
     *     @OA\Property(property="user_id", type="integer", example=5),
     *     @OA\Property(property="news_id", type="integer", example="3"),
     *     @OA\Property(property="title", type="string", example="不具合を解消しました"),
     *     @OA\Property(property="content", type="string", example="ログインが出来ない不具合を解消しました")
     * )
     */

    /**
     * @OA\Get(
     *     path="api/news",
     *     description="user_idカラムがログインしているユーザもしくは運営ナンバー(0)に紐づく、ニュースデータをページネーション形式で取得する(件数：15件)",
     *     produces={"application/json"},
     *     tags={"news"},
     *     @OA\Response(
     *         response=200,
     *         description="Success / ニュースデータを表示",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 description="ニュースデータを表示",
     *                 @OA\Items(
     *                      ref="#/components/schemas/news_list"
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
     *                 example="ニュースを取得出来ませんでした"
     *             )
     *         )
     *     ),
     * )
     * 
     * ニュース一覧の表示用アクション
     */
    public function index(Request $request)
    {
        try {
            // 検索条件
            $conditions = [];
            $conditions['@innews.user_id'] = [0, Auth::user()->id];
            $conditions['@>equalnews.created_at'] = Auth::user()->created_at;
            
            // ソート条件
            $order = [];
            $order['news.created_at'] = 'desc';
    
            $data = $this->db->searchQueryPaginate($conditions, $order);
            
            return response()->json(['news' => $data], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            Log::error(config('const.SystemMessage.SYSTEM_ERR').get_class($this).'::'.__FUNCTION__.":".$e->getMessage());

            return response()->json([
              'error_message' => config('const.News.GET_ERR'),
              'status'        => 500,
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * @OA\Get(
     *     path="api/news/{news}",
     *     description="指定したニュースデータを取得する",
     *     produces={"application/json"},
     *     tags={"news"},
     *     @OA\Parameter(
     *         name="news",
     *         description="ニュースID",
     *         in="path",
     *         required=true,
     *         type="string"
     *     ),
     *     @OA\Parameter(
     *         name="user_id",
     *         description="ユーザID",
     *         in="query",
     *         required=false,
     *         type="string"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success / 指定したニュースデータを表示",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="news",
     *                 type="object",
     *                 description="指定したニュースデータを表示",
     *                 ref="#/components/schemas/news_list"
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request error / 存在しないニュースをリクエストした場合、エラー用のメッセージを表示",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="error_message",
     *                 type="string",
     *                 description="エラー用のメッセージを表示",
     *                 example="指定したニュースは存在しません"
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
     *                 example="ニュースを取得出来ませんでした"
     *             )
     *         )
     *     ),
     * )
     * 
     * 
     * ニュース詳細の表示用アクション
     */
    public function show(Request $request, $news)
    {
        try {
            // user_idを設定
            $user_id = 0;
            if($request->user_id) $user_id = $request->user_id;
            
            // 検索条件の設定
            $conditions = [
                'news.user_id'            => $user_id,
                'news.news_id'            => $news,
                '@>equalnews.created_at'  => Auth::user()->created_at
            ];
            
            $data = $this->db->baseSearchFirst($conditions);
            
            // ニュースが存在しない場合
            if(empty($data)) {
                return response()->json(['error_message' => config('const.News.SEARCH_ERR')], 400, [], JSON_UNESCAPED_UNICODE);    
            }
            
            return response()->json(['news' => $data], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            Log::error(config('const.SystemMessage.SYSTEM_ERR').get_class($this).'::'.__FUNCTION__.":".$e->getMessage());

            return response()->json([
              'error_message' => config('const.News.GET_ERR'),
              'status'        => 500,
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * @OA\Post(
     *     path="api/news/validate",
     *     description="ニュース登録・更新時のバリデーションを実行する",
     *     produces={"application/json"},
     *     tags={"news"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="request",
     *                 type="object",
     *                 description="リクエストボディのjsonのプロパティの例",
     *                 ref="#/components/schemas/news_register"
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success / バリデーションチェック通過のメッセージをリターン",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="validate_status",
     *                 type="string",
     *                 description="バリデーションチェック通過のメッセージをリターン",
     *                 example="OK"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request error / バリデーションエラーのメッセージを表示",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="errors",
     *                 type="string",
     *                 description="バリデーションエラーのメッセージを表示",
     *                 ref="#/components/schemas/news_errors"
     *             )
     *         )
     *     ),
     * )
     * 
     * ニュースバリデーション用メソッド
     *   ※データ登録時には非同期処理で常時確認に使用
     */
    public function newsValidate(NewsRegisterRequest $request)
    {
        return;
    }

    /**
     * @OA\Post(
     *     path="api/news",
     *     description="アルバムデータを保存する",
     *     produces={"application/json"},
     *     tags={"news"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="request",
     *                 type="object",
     *                 description="リクエストボディのjsonのプロパティの例",
     *                 ref="#/components/schemas/news_register"
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success / 保存成功のメッセージを表示",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="info_message",
     *                 type="string",
     *                 description="保存成功のメッセージを表示",
     *                 example="ニュースを登録しました"
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
     *                 example="ニュースの登録に失敗しました"
     *             )
     *         )
     *     ),
     * )
     * 
     * ニュース登録処理用アクション
     * ※管理者の全体向けメッセージ登録時に利用
     */
    public function store(NewsRegisterRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->all();

            $data['update_user_id'] = Auth::user()->id;

            // news_idの最大値に1を加算
            $data['news_id'] = $this->db->getNewsId(0);
    
            // データの保存処理
            $data = $this->db->save($data);

            // 未読管理テーブルへの追加
            $users = $this->db->getAllUser();
            $ndata = [
                'news_user_id'  => $data->user_id,
                'news_id'       => $data->news_id
            ];

            $this->db->savePublicNread($ndata, $users);

            DB::commit();
            return response()->json([
                'info_message' => config('const.News.REGISTER_INFO'),
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            DB::rollback();
            Log::error(config('const.SystemMessage.SYSTEM_ERR').get_class($this).'::'.__FUNCTION__.":".$e->getMessage());

            // 作成失敗時はエラーメッセージを返す
            return response()->json([
              'error_message' => config('const.News.REGISTER_ERR'),
              'status'        => 500,
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * @OA\Put(
     *     path="api/news/{news}",
     *     description="ニュースデータを更新保存する",
     *     produces={"application/json"},
     *     tags={"news"},
     *     @OA\Parameter(
     *         name="news",
     *         description="ニュースID",
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
     *                 ref="#/components/schemas/news_update"
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success / 保存成功のメッセージを表示",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="info_message",
     *                 type="string",
     *                 description="保存成功のメッセージを表示",
     *                 example="ニュースを登録しました"
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
     *                 example="ニュースの登録に失敗しました"
     *             )
     *         )
     *     ),
     * )
     * 
     * ニュース更新処理用アクション
     */
    public function update(NewsRegisterRequest $request, $news)
    {
        DB::beginTransaction();
        try {

            $data = $request->all();

            $data['news_id'] = $news;
            
            // データの保存処理
            $this->db->save($data);

            DB::commit();
            return response()->json([
                'info_message' => config('const.News.REGISTER_INFO'),
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            DB::rollback();
            Log::error(config('const.SystemMessage.SYSTEM_ERR').get_class($this).'::'.__FUNCTION__.":".$e->getMessage());

            // 作成失敗時はエラーメッセージを返す
            return response()->json([
              'error_message' => config('const.News.REGISTER_ERR'),
              'status'        => 500,
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * @OA\Delete(
     *     path="api/news/{news}",
     *     description="ニュースデータを論理削除する",
     *     produces={"application/json"},
     *     tags={"news"},
     *     @OA\Parameter(
     *         name="news",
     *         description="ニュースID",
     *         in="path",
     *         required=true,
     *         type="string"
     *     ),
     *     @OA\Parameter(
     *         name="user_id",
     *         description="削除対象のニュースに紐づくユーザID",
     *         in="query",
     *         required=false,
     *         type="string"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success / 物理削除成功のメッセージを表示",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="info_message",
     *                 type="string",
     *                 description="物理削除成功のメッセージを表示",
     *                 example="ニュースの削除が完了しました"
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
     *                 example="サーバーエラーによりニュースの削除に失敗しました。管理者にお問い合わせください"
     *             )
     *         )
     *     ),
     * )
     * 
     * ニュースの削除用アクション
     */
    public function destroy(Request $request, $news)
    {
        try {
            // バリデーションチェック
            if(Auth::user()->status !== config('const.User.ADMIN')) throw new Exception('管理者権限のないユーザがニュースの削除を実行しようとしました');
            if(!$this->db->baseAdminCertification($request->onetime_password)) throw new Exception('ワンタイムパスワードの不一致により、管理者認証に失敗しました');
            
            DB::beginTransaction();

            $key = [
                'news_id' => $news
            ];
            $key['user_id'] = 0;
            // 全体用以外のニュースを削除する場合
            if($request->user_id) {
                $key['user_id'] = $request->user_id;
            }

            // データ削除
            $this->db->delete($key);
            
            DB::commit();
            return response()->json(['info_message' => config('const.News.DELETE_INFO')], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            DB::rollback();
            Log::error(config('const.SystemMessage.SYSTEM_ERR').get_class($this).'::'.__FUNCTION__.":".$e->getMessage());

            return response()->json([
              'error_message' => config('const.News.DELETE_ERR'),
              'status'        => 500,
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }
}
