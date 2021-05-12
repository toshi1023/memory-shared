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
            if($request->input('title@like')) $conditions = Common::setConditions($request);
            
            // ソート条件
            $order = [];
            if($request->sort_updated_at) $order = Common::setOrder($request);
    
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
            // 検索条件の設定
            $conditions = [
                'id' => $news
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
    public function groupValidate(NewsRegisterRequest $request)
    {
        return;
    }

    /**
     * ニュース登録処理用アクション
     */
    public function store(NewsRegisterRequest $request)
    {
        DB::beginTransaction();
        try {

            $data = $request->all();
    
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
     */
    public function update(NewsRegisterRequest $request)
    {
        DB::beginTransaction();
        try {

            $data = $request->all();
            
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
            DB::beginTransaction();

            // データ削除
            $this->db->baseDelete($news);
            
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
