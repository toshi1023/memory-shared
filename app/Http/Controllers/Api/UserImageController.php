<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserImage;
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
    public function userImageValidate(UserImageRegisterRequest $request)
    {
        return [
            'validate_status' => config('const.SystemMessage.VALIDATE_STATUS')
        ];
    }

    /**
     * 画像保存処理用アクション
     */
    public function store(UserImageRegisterRequest $request)
    {
        DB::beginTransaction();
        try {
            // ブラックリスト、ホワイトリスト作成(パラメーター例: black_list[] = 1, black_list[] = 2)
            $blacklist = $request->input('black_list') ? Common::setJsonType($request->input('black_list')) : null;
            $whitelist = $request->input('white_list') ? Common::setJsonType($request->input('white_list')) : null;

            // 保存データの設定
            $data = [];
            // データの保存処理(仮保存)
            $userImage = UserImage::create([
                'user_id'       => $request->input('user_id'),
                'album_id'      => $request->input('album_id'),
                'image_file'    => config('const.UserImage.BEFORE_SAVE_NAME'),
                'type'          => $request->input('type'),
                'black_list'    => $blacklist,
                'white_list'    => $whitelist
            ]);
            
            // ファイル名の生成
            $filename = Common::getUniqueFilename($request->file('image_file'), $userImage->id);
            $data['id'] = $userImage->id;
            $data['image_file'] = $filename;
            // データの保存処理(正式保存)
            $this->db->save($data);

            // 画像の保存処理
            Common::fileSave($request->file('image_file'), config('const.Aws.MAIN'), $request->input('album_id'), $filename);

            DB::commit();
            return response()->json([
                'info_message' => config('const.UserImage.REGISTER_INFO'),
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            DB::rollback();
            Log::error(config('const.SystemMessage.SYSTEM_ERR').get_class($this).'::'.__FUNCTION__.":".$e->getMessage());

            // ロールバックした場合は仮保存したデータも物理削除
            $this->db->baseForceDelete($userImage->id);

            // 作成失敗時はエラーメッセージを返す
            return response()->json([
              'error_message' => config('const.UserImage.REGISTER_ERR'),
              'status'        => 500,
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * 画像データ削除(論理削除)
     */
    public function destroy($group, $album, $image)
    {
        try {
            DB::beginTransaction();

            // データ削除
            $this->db->baseDelete($image);
            
            DB::commit();
            return response()->json(['info_message' => config('const.UserImage.DELETE_INFO')], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            DB::rollback();
            Log::error(config('const.SystemMessage.SYSTEM_ERR').get_class($this).'::'.__FUNCTION__.":".$e->getMessage());

            return response()->json([
              'error_message' => config('const.UserImage.DELETE_ERR'),
              'status'        => 500,
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }
}
