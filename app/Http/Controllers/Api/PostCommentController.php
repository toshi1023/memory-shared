<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\PostComment\PostCommentRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PostCommentController extends Controller
{
    protected $db;

    public function __construct(PostCommentRepositoryInterface $database)
    {
        $this->db = $database;
    }

    /**
     * 投稿コメント一覧の表示用アクション
     */
    public function index(Request $request, $group, $post)
    {
        try {
            // バリデーションチェック
            if(!$this->db->baseConfirmGroupMember(Auth::user()->id, $group)) throw new Exception('グループに加盟していないユーザがアクセスを要求しました');

            // 検索条件
            $conditions = [];
            $conditions['post_id'] = $post;
            
            // ソート条件
            $order = [];
            $order['updated_at'] = 'desc';
    
            $data = $this->db->searchQuery($conditions, $order);
            
            return response()->json(['comments' => $data], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            Log::error(config('const.SystemMessage.SYSTEM_ERR').get_class($this).'::'.__FUNCTION__.":".$e->getMessage());

            return response()->json([
              'error_message' => config('const.PostComment.GET_ERR'),
              'status'        => 500,
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * 投稿コメント登録処理用アクション
     */
    public function store(Request $request, $group, $post)
    {
        DB::beginTransaction();
        try {
            // バリデーションチェック
            if(!$this->db->baseConfirmGroupMember(Auth::user()->id, $group)) throw new Exception('グループに加盟していないユーザがコメントの作成を実行しようとしました');

            $data = $request->all();

            $data['post_id'] = $post;
            $data['user_id'] = Auth::user()->id;

            // データの保存処理
            $data = $this->db->save($data);

            // コメント再取得
            $conditions = [
                'post_id' => $data->post_id
            ];
            $order = [
                'updated_at' => 'desc'
            ];
            $data = $this->db->searchQuery($conditions, $order);

            DB::commit();
            return response()->json([
                'info_message' => config('const.PostComment.REGISTER_INFO'),
                'comments'     => $data
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            DB::rollback();
            Log::error(config('const.SystemMessage.SYSTEM_ERR').get_class($this).'::'.__FUNCTION__.":".$e->getMessage());

            // 作成失敗時はエラーメッセージを返す
            return response()->json([
              'error_message' => config('const.PostComment.REGISTER_ERR'),
              'status'        => 500,
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * 投稿コメントの削除用アクション
     * 引数2: 投稿ID
     */
    public function destroy(Request $request, $group, $post, $comment)
    {
        try {
            // バリデーションチェック
            $postComment = $this->db->searchFirst(['id' => $comment]);
            if(Auth::user()->id !== $postComment->user_id) throw new Exception('作成者でないユーザがコメントの削除を実行しようとしました');

            DB::beginTransaction();

            // データ削除
            $this->db->baseDelete($comment);
            
            DB::commit();
            return response()->json(['info_message' => config('const.PostComment.DELETE_INFO')], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            DB::rollback();
            Log::error(config('const.SystemMessage.SYSTEM_ERR').get_class($this).'::'.__FUNCTION__.":".$e->getMessage());

            return response()->json([
              'error_message' => config('const.PostComment.DELETE_ERR'),
              'status'        => 500,
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }
}
