<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Album\AlbumRepositoryInterface;
use App\Http\Requests\AlbumRegisterRequest;
use App\Lib\Common;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class AlbumController extends Controller
{
    protected $db;

    public function __construct(AlbumRepositoryInterface $database)
    {
        $this->db = $database;
    }

    /**
     * グループの所有するアルバム一覧
     */
    public function index(Request $request, $group)
    {
        try {
            // 検索条件
            $conditions = [];
            $conditions['group_id'] = $group;
            if(
                $request->input('created_at@>equal') || 
                $request->input('created_at@<equal') || 
                $request->input('updated_at@>equal') ||
                $request->input('updated_at@<equal') ||
                $request->input('name@like')
            ) {
                $conditions = Common::setConditions($request);
            }
            
            // ソート条件
            $order = [];
            if(
                $request->sort_name || 
                $request->sort_created_at ||
                $request->sort_updated_at
            ) {
                $order = Common::setOrder($request);
            }
    
            $data = $this->db->baseSearchQueryPaginate($conditions, $order, 20);
            
            return response()->json($data, 200, [], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            Log::error(config('const.SystemMessage.SYSTEM_ERR').get_class($this).'::'.__FUNCTION__.":".$e->getMessage());

            return response()->json([
              'error_message' => config('const.Album.GET_ERR'),
              'status'        => 500,
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * アルバム詳細
     */
    public function show(Request $request, $group, $album)
    {
        try {
            $data = [];

            // 検索条件
            $conditions = [];
            $conditions['id'] = $album;

            // アルバム情報取得
            $data['album'] = $this->db->baseSearchFirst($conditions);
            // 画像情報取得(black_list等の取得例: $data['image'][0]['black_list'][3])
            $data['image'] = $this->db->getImages(['album_id' => $data['album']->id]);
            // 動画情報取得
            $data['video'] = $this->db->getVideos(['album_id' => $data['album']->id]);
            
            return response()->json($data, 200, [], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            Log::error(config('const.SystemMessage.SYSTEM_ERR').get_class($this).'::'.__FUNCTION__.":".$e->getMessage());

            return response()->json([
              'error_message' => config('const.Album.GET_ERR'),
              'status'        => 500,
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * アルバムバリデーション用メソッド
     *   ※データ登録時には非同期処理で常時確認に使用
     */
    public function albumValidate(AlbumRegisterRequest $request)
    {
        return;
    }

    /**
     * アルバム作成処理用アクション
     */
    public function store(AlbumRegisterRequest $request)
    {
        DB::beginTransaction();
        try {
            // データの配列化
            $data = $request->all();

            // ファイル名の生成
            $filename = null;
            if ($request->file('image_file')){
                $filename = Common::getFilename($request->file('image_file'));
                $data['image_file'] = $filename;
            }
    
            // データの保存処理
            $data = $this->db->save($data);

            // ファイルの保存処理
            if($request->file('image_file')) {
                Common::fileSave($request->file('image_file'), config('const.Aws.USER'), $data->id, $filename);
            }

            DB::commit();
            return response()->json([
                'info_message' => config('const.Album.REGISTER_INFO'),
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            DB::rollback();
            Log::error(config('const.SystemMessage.SYSTEM_ERR').get_class($this).'::'.__FUNCTION__.":".$e->getMessage());

            // 作成失敗時はエラーメッセージを返す
            return response()->json([
              'error_message' => config('const.Album.REGISTER_ERR'),
              'status'        => 500,
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * アルバム更新処理用アクション
     */
    public function update(AlbumRegisterRequest $request)
    {
        DB::beginTransaction();
        try {

            $data = $request->all();

            // ファイル名の生成
            $filename = null;
            if ($request->file('image_file')){
                $filename = Common::getFilename($request->file('image_file'));
                $data['image_file'] = $filename;
            }
    
            // データの保存処理
            $data = $this->db->save($data);

            // ファイルの保存処理
            if($request->file('image_file')) {
                Common::fileSave($request->file('image_file'), config('const.Aws.USER'), $data->id, $filename);
            }

            DB::commit();
            return response()->json([
                'info_message' => config('const.Album.REGISTER_INFO'),
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            DB::rollback();
            Log::error(config('const.SystemMessage.SYSTEM_ERR').get_class($this).'::'.__FUNCTION__.":".$e->getMessage());

            // 作成失敗時はエラーメッセージを返す
            return response()->json([
              'error_message' => config('const.Album.REGISTER_ERR'),
              'status'        => 500,
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * アルバムの削除用アクション
     */
    public function destroy(Request $request, $group, $album)
    {
        try {
            DB::beginTransaction();

            // データ削除
            $this->db->baseDelete($album);
            
            DB::commit();
            return response()->json(['info_message' => config('const.Album.DELETE_INFO')], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            DB::rollback();
            Log::error(config('const.SystemMessage.SYSTEM_ERR').get_class($this).'::'.__FUNCTION__.":".$e->getMessage());

            return response()->json([
              'error_message' => config('const.Album.DELETE_ERR'),
              'status'        => 500,
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }
}
