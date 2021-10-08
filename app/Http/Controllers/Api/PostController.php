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
            Log::error(config('const.SystemMessage.SYSTEM_ERR').get_class($this).'::'.__FUNCTION__.":".$e->getMessage());

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
            Log::error(config('const.SystemMessage.SYSTEM_ERR').get_class($this).'::'.__FUNCTION__.":".$e->getMessage());

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
            
            DB::commit();
            return response()->json(['info_message' => config('const.Post.DELETE_INFO')], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            DB::rollback();
            Log::error(config('const.SystemMessage.SYSTEM_ERR').get_class($this).'::'.__FUNCTION__.":".$e->getMessage());

            return response()->json([
              'error_message' => config('const.Post.DELETE_ERR'),
              'status'        => 500,
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }
}
