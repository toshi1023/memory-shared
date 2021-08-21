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
     * 【ハンバーガーメニュー】
     * ニュース一覧の表示用アクション
     */
    public function index(Request $request)
    {
        try {
            // 検索条件
            $conditions = [];
            $conditions['@innews.user_id'] = [0, Auth::user()->id];
            // $conditions['@innews.user_id'] = [0, $request->user_id];
            if($request->input('title@like')) $conditions = Common::setConditions($request);
            
            // ソート条件
            $order = [];
            $order['news.created_at'] = 'desc';
    
            $data = $this->db->searchQuery($conditions, $order);
            
            return response()->json($data, 200, [], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            Log::error(config('const.SystemMessage.SYSTEM_ERR').get_class($this).'::'.__FUNCTION__.":".$e->getMessage());

            return response()->json([
              'error_message' => config('const.News.GET_ERR'),
              'status'        => 500,
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * 【ハンバーガーメニュー】
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
                'user_id'    => $user_id,
                'news_id'    => $news
            ];
            
            $data = $this->db->baseSearchFirst($conditions);
            
            // ニュースが存在しない場合
            if(empty($data)) {
                return response()->json(['error_message' => config('const.News.SEARCH_ERR')], 404, [], JSON_UNESCAPED_UNICODE);    
            }
            
            return response()->json($data, 200, [], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            Log::error(config('const.SystemMessage.SYSTEM_ERR').get_class($this).'::'.__FUNCTION__.":".$e->getMessage());

            return response()->json([
              'error_message' => config('const.News.GET_ERR'),
              'status'        => 500,
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * ニュースバリデーション用メソッド
     *   ※データ登録時には非同期処理で常時確認に使用
     */
    public function newsValidate(NewsRegisterRequest $request)
    {
        return;
    }

    /**
     * ニュース登録処理用アクション
     * ※管理者の全体向けメッセージ登録時に利用
     */
    public function store(NewsRegisterRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->all();

            $data['user_id'] = 0;

            // news_idの最大値に1を加算
            $data['news_id'] = $this->db->baseSearchFirst(['user_id' => 0], ['news_id' => 'desc'])->news_id + 1;
    
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
     * ニュース更新処理用アクション
     * 引数2: ニュースID
     */
    public function update(NewsRegisterRequest $request, $news)
    {
        DB::beginTransaction();
        try {

            $data = $request->all();

            $data['user_id'] = 0;
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
     * ニュースの削除用アクション
     * 引数2: ニュースID
     */
    public function destroy(Request $request, $news)
    {
        try {
            // バリデーションチェック
            if(Auth::user()->status !== config('const.User.ADMIN')) throw new Exception('管理者権限のないユーザがニュースの削除を実行しようとしました');
            if(!$this->db->baseAdminCertification($request->onetime_password)) throw new Exception('ワンタイムパスワードの不一致により、管理者認証に失敗しました');

            DB::beginTransaction();

            $user_id = 0;
            // 全体用以外のニュースを削除する場合
            if($request->user_id) {
                $user_id = $request->user_id;
            }

            // データ削除
            $this->db->delete($user_id, $news);
            
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
