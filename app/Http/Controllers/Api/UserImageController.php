<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\UserImage\UserImageRepositoryInterface;
use App\Http\Requests\UserImageRegisterRequest;
use App\Lib\Common;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class UserImageController extends Controller
{
    protected $db;

    public function __construct(UserImageRepositoryInterface $database)
    {
        $this->db = $database;
    }

    /**
     * 画像バリデーション用メソッド
     *   ※データ登録時には非同期処理で常時確認に使用
     */
    public function albumValidate(UserImageRegisterRequest $request)
    {
        return;
    }

    /**
     * 画像保存処理用アクション
     */
    public function store(UserImageRegisterRequest $request)
    {
        DB::beginTransaction();
        try {
            // dd($request->input('user_id'));
            foreach($request->file('image_file') as $key => $value) {
                // 保存データの設定
                $data = [];
                $data['user_id'] = $request->input('user_id');
                $data['album_id'] = $request->input('album_id');
                // ファイル名の生成
                $filename = Common::getFilename($value);
                $data['image_file'] = $filename;
                dump($value);
                dump($data);
                // // データの保存処理
                // $this->db->save($data);

                // // 画像の保存処理
                // Common::fileSave($value, config('const.Aws.MAIN'), $request->input('album_id'), $filename);
            }
            exit;
            DB::commit();
            return response()->json([
                'info_message' => config('const.UserImage.REGISTER_INFO'),
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            DB::rollback();
            Log::error(config('const.SystemMessage.SYSTEM_ERR').get_class($this).'::'.__FUNCTION__.":".$e->getMessage());

            // 作成失敗時はエラーメッセージを返す
            return response()->json([
              'error_message' => config('const.UserImage.REGISTER_ERR'),
              'status'        => 500,
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }
}
