<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Post\PostRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    protected $db;

    public function __construct(PostRepositoryInterface $database)
    {
        $this->db = $database;
    }

    /**
     * @OA\Schema(
     *     schema="post_list",
     *     required={"id", "content", "user_id", "group_id", "update_user_id", "creater_at", "updated_at", "deleted_at"},
     *     @OA\Property(property="id", type="integer", example=1),
     *     @OA\Property(property="content", type="string", example="●月●日に集会やりましょう！参加者はコメント欄に書き込みしてねー"),
     *     @OA\Property(property="user_id", type="integer", example=3),
     *     @OA\Property(property="group_id", type="integer", example=4),
     *     @OA\Property(property="update_user_id", type="integer", example=3),
     *     @OA\Property(property="created_at", type="string", example="2021-04-25 12:02:55"),
     *     @OA\Property(property="updated_at", type="string", example="2021-04-28 14:13:00"),
     *     @OA\Property(property="deleted_at", type="string", example="null"),
     *     @OA\Property(property="user", type="object", required={"id", "name", "image_file"},
     *          @OA\Property(property="id", type="integer", example=3),
     *          @OA\Property(property="name", type="string", example="test user 3"),
     *          @OA\Property(property="image_file", type="string", example="xxxxoooo.jpg"),
     *     ),
     * )
     */

     /**
     * @OA\Get(
     *     path="api/groups/{group}/posts",
     *     description="選択したグループの投稿内容をページネーション形式で取得する(件数：10件)",
     *     produces={"application/json"},
     *     tags={"posts"},
     *     @OA\Parameter(
     *         name="group",
     *         description="グループID",
     *         in="path",
     *         required=true,
     *         type="string"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success / 選択したグループの投稿内容データを表示",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="posts",
     *                 type="array",
     *                 description="選択したグループの投稿内容データを表示",
     *                 @OA\Items(
     *                      ref="#/components/schemas/post_list"
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
     *                 description="サーバエラー用のメッセージを表示(グループに加盟していないユーザが本リクエストを送ってきた場合も対象)",
     *                 example="投稿を取得出来ませんでした"
     *             )
     *         )
     *     ),
     * )
     * 
     * 投稿一覧の表示用アクション
     */
    public function index(Request $request, $group)
    {
        try {
            // バリデーションチェック
            if(!$this->db->baseConfirmGroupMember(Auth::user()->id, $group)) throw new Exception('グループに加盟していないユーザがアクセスを要求しました');
            // 検索条件
            $conditions = [];
            $conditions['posts.group_id'] = $group;
            
            // ソート条件
            $order = [];
            $order['posts.updated_at'] = 'desc';
    
            $data = $this->db->searchQueryPaginate($conditions, $order);
            
            return response()->json(['posts' => $data], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            $this->getErrorLog($request, $e, get_class($this), __FUNCTION__);

            return response()->json([
              'error_message' => config('const.Post.GET_ERR'),
              'status'        => 500,
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * 投稿登録処理用アクション
     */
    public function store(Request $request, $group)
    {
        DB::beginTransaction();
        try {
            // バリデーションチェック
            if(!$this->db->baseConfirmGroupMember(Auth::user()->id, $group)) throw new Exception('グループに加盟していないユーザが投稿の作成を実行しようとしました');

            $data = $request->all();

            $data['group_id'] = $group;
            $data['user_id'] = Auth::user()->id;
    
            // データの保存処理
            $data = $this->db->save($data);

            // ニューステーブルへの保存
            $groupInfo = $this->db->getGroupInfo($group);
            $groupMember = $this->db->getGroupMember($group);

            foreach($groupMember as $value) {
                $this->db->savePostInfo($value->user_id, Auth::user()->name, $groupInfo->name, Auth::user()->id);
            }

            DB::commit();
            return response()->json([
                'info_message' => config('const.Post.REGISTER_INFO'),
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            DB::rollback();
            $this->getErrorLog($request, $e, get_class($this), __FUNCTION__);

            // 作成失敗時はエラーメッセージを返す
            return response()->json([
              'error_message' => config('const.Post.REGISTER_ERR'),
              'status'        => 500,
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * 投稿の削除用アクション
     * 引数2: 投稿ID
     */
    public function destroy(Request $request, $group, $post)
    {
        try {
            // バリデーションチェック
            $postInfo = $this->db->searchFirst(['id' => $post]);
            if(Auth::user()->id !== $postInfo->user_id) throw new Exception('作成者でないユーザが投稿の削除を実行しようとしました');

            DB::beginTransaction();

            // データ削除
            $this->db->baseDelete($post);

            // 投稿に紐づくコメントも削除
            $comments = $this->db->getPostComment($post);
            
            foreach($comments as $value) {
                $this->db->deletePostComment($value->id);
            }

            // 投稿データの再取得
            // 検索条件
            $conditions = [];
            $conditions['posts.group_id'] = $group;
            
            // ソート条件
            $order = [];
            $order['posts.updated_at'] = 'desc';
    
            $data = $this->db->searchQueryPaginate($conditions, $order);
            
            DB::commit();
            return response()->json([
                'info_message' => config('const.Post.DELETE_INFO'),
                'posts'        => $data,
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            DB::rollback();
            $this->getErrorLog($request, $e, get_class($this), __FUNCTION__);

            return response()->json([
              'error_message' => config('const.Post.DELETE_ERR'),
              'status'        => 500,
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }
}
