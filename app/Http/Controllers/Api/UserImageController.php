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
     * @OA\Schema(
     *     schema="image_list",
     *     required={"id", "image_file", "user_id", "album_id", "black_list", "white_list", "update_user_id", "created_at", "updated_at", "deleted_at"},
     *     @OA\Property(property="id", type="integer", example=5),
     *     @OA\Property(property="image_file", type="string", example="xxxxoooo.png"),
     *     @OA\Property(property="user_id", type="integer", example="4"),
     *     @OA\Property(property="album_id", type="integer", example="1"),
     *     @OA\Property(property="black_list", type="json", example="{black_list: 1, 3, 10}"),
     *     @OA\Property(property="white_list", type="json", example="null"),
     *     @OA\Property(property="update_user_id", type="integer", example="4"),
     *     @OA\Property(property="created_at", type="string", example="2021-04-25 12:02:55"),
     *     @OA\Property(property="updated_at", type="string", example="2021-04-28 14:13:00"),
     *     @OA\Property(property="deleted_at", type="string", example="null"),
     * )
     */

     /**
     * @OA\Get(
     *     path="api/groups/{group}/albums/{album}/images",
     *     description="選択したアルバムデータに紐づく画像情報をページネーション形式で取得する(件数：30件)",
     *     produces={"application/json"},
     *     tags={"user_images"},
     *     @OA\Parameter(
     *         name="group",
     *         description="グループID",
     *         in="path",
     *         required=true,
     *         type="string"
     *     ),
     *     @OA\Parameter(
     *         name="album",
     *         description="アルバムID",
     *         in="path",
     *         required=true,
     *         type="string"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success / 指定したアルバムの画像データを表示",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 description="指定したアルバムの画像データを表示",
     *                 @OA\Items(
     *                      ref="#/components/schemas/image_list"
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
     *                 description="サーバエラー用のメッセージを表示",
     *                 example="画像を取得出来ませんでした"
     *             )
     *         )
     *     ),
     * )
     * 
     * 画像取得用メソッド
     */
    public function index(Request $request, $group, $album)
    {
        try {
            // 検索条件
            $conditions = [];
            $conditions['album_id'] = $album;

            // ソート条件
            $order = [];
            $order['id'] = 'desc';

            // 画像情報取得(black_list等の取得例: $data['image'][0]['black_list'][3])
            $data = $this->db->searchQueryPaginate($conditions, $order);
            
            return response()->json(['images' => $data], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            $this->getErrorLog($request, $e, get_class($this), __FUNCTION__);

            return response()->json([
              'error_message' => config('const.UserImage.GET_ERR'),
              'status'        => 500,
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
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
            $this->getErrorLog($request, $e, get_class($this), __FUNCTION__);

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
    public function destroy(Request $request, $group, $album, $image)
    {
        try {
            DB::beginTransaction();

            // データ削除
            $this->db->baseDelete($image);
            
            DB::commit();
            return response()->json(['info_message' => config('const.UserImage.DELETE_INFO')], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            DB::rollback();
            $this->getErrorLog($request, $e, get_class($this), __FUNCTION__);

            return response()->json([
              'error_message' => config('const.UserImage.DELETE_ERR'),
              'status'        => 500,
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }
}
