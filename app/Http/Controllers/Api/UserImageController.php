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
        return;
    }

    /**
     * 画像保存処理用アクション
     */
    public function store(UserImageRegisterRequest $request)
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
                $userImage = UserImage::create([
                    'user_id'       => $request->input('user_id'),
                    'album_id'      => $request->input('album_id'),
                    'image_file'    => config('const.UserImage.BEFORE_SAVE_NAME'),
                    'black_list'    => $blacklist,
                    'white_list'    => $whitelist
                ]);
                
                // ファイル名の生成
                $filename = Common::getUniqueFilename($value, $userImage->id);
                $data['id'] = $userImage->id;
                $data['image_file'] = $filename;
                $data['user_id'] = $userImage->user_id;
                $data['album_id'] = $userImage->album_id;
                // データの保存処理(正式保存)
                $this->db->save($data);
                
                // // 画像の保存処理
                // Common::fileSave($value, config('const.Aws.MAIN'), $request->input('album_id'), $filename);
                // dump($data);
            }

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

    /**
     * 画像データ削除(論理削除)
     */
    public function destroy(Request $request, $image)
    {
        try {
            DB::beginTransaction();
            // 検索条件の設定
            $conditions = [
                'image_file'      => $image
            ];
            
            $data = $this->db->baseSearchFirst($conditions);

            // データ削除
            $this->db->baseDelete($data->id);
            
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
