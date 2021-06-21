<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserVideo;
use App\Repositories\UserVideo\UserVideoRepositoryInterface;
use App\Http\Requests\UserVideoRegisterRequest;
use App\Lib\Common;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class UserVideoController extends Controller
{
    protected $db;

    public function __construct(UserVideoRepositoryInterface $database)
    {
        $this->db = $database;
    }

    /**
     * 動画バリデーション用メソッド
     *   ※データ登録時には非同期処理で常時確認に使用
     */
    public function userVideoValidate(UserVideoRegisterRequest $request)
    {
        return;
    }

    /**
     * 動画保存処理用アクション
     */
    public function store(UserVideoRegisterRequest $request)
    {
        DB::beginTransaction();
        try {
            // ブラックリスト、ホワイトリスト作成
            $blacklist = $request->input('black_list') ? Common::setJsonType($request->input('black_list')) : null;
            $whitelist = $request->input('white_list') ? Common::setJsonType($request->input('white_list')) : null;

            foreach($request->file('image_file') as $key => $value) {
                // 保存データの設定
                $data = [];
                // データの保存処理(仮保存)
                $userVideo = UserVideo::create([
                    'user_id'       => $request->input('user_id'),
                    'title'         => $request->input('title') ? $request->input('title') : config('const.UserVideo.TITLE'),
                    'album_id'      => $request->input('album_id'),
                    'image_file'    => config('const.UserVideo.BEFORE_SAVE_NAME'),
                    'black_list'    => $blacklist,
                    'white_list'    => $whitelist
                ]);
                
                // ファイル名の生成
                $filename = Common::getUniqueFilename($value, $userVideo->id);
                $data['id'] = $userVideo->id;
                $data['image_file'] = $filename;
                $data['user_id'] = $userVideo->user_id;
                $data['album_id'] = $userVideo->album_id;
                // データの保存処理(正式保存)
                $this->db->save($data);
                
                // // 動画の保存処理
                // Common::fileSave($value, config('const.Aws.MAIN'), $request->input('album_id'), $filename);
                // dump($data);
            }

            DB::commit();
            return response()->json([
                'info_message' => config('const.UserVideo.REGISTER_INFO'),
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            DB::rollback();
            Log::error(config('const.SystemMessage.SYSTEM_ERR').get_class($this).'::'.__FUNCTION__.":".$e->getMessage());

            // 作成失敗時はエラーメッセージを返す
            return response()->json([
              'error_message' => config('const.UserVideo.REGISTER_ERR'),
              'status'        => 500,
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * 動画データ削除(論理削除)
     */
    public function destroy($group, $album, $video)
    {
        try {
            DB::beginTransaction();

            // データ削除
            $this->db->baseDelete($video);
            
            DB::commit();
            return response()->json(['info_message' => config('const.UserVideo.DELETE_INFO')], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            DB::rollback();
            Log::error(config('const.SystemMessage.SYSTEM_ERR').get_class($this).'::'.__FUNCTION__.":".$e->getMessage());

            return response()->json([
              'error_message' => config('const.UserVideo.DELETE_ERR'),
              'status'        => 500,
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }
}
