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
     * @OA\Schema(
     *     schema="video_list",
     *     required={"id", "image_file", "user_id", "album_id", "black_list", "white_list", "update_user_id", "created_at", "updated_at", "deleted_at"},
     *     @OA\Property(property="id", type="integer", example=5),
     *     @OA\Property(property="image_file", type="string", example="xxxxoooo.mp4"),
     *     @OA\Property(property="user_id", type="integer", example="4"),
     *     @OA\Property(property="album_id", type="integer", example="1"),
     *     @OA\Property(property="black_list", type="json", example="null"),
     *     @OA\Property(property="white_list", type="json", example="{white_list: 2, 6, 8}"),
     *     @OA\Property(property="update_user_id", type="integer", example="4"),
     *     @OA\Property(property="created_at", type="string", example="2021-04-25 12:02:55"),
     *     @OA\Property(property="updated_at", type="string", example="2021-04-28 14:13:00"),
     *     @OA\Property(property="deleted_at", type="string", example="null"),
     * )
     */

     /**
     * @OA\Get(
     *     path="api/groups/{group}/albums/{album}/videos",
     *     description="選択したアルバムデータに紐づく動画情報をページネーション形式で取得する(件数：10件)",
     *     produces={"application/json"},
     *     tags={"user_videos"},
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
     *         description="Success / 指定したアルバムの動画データを表示",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 description="指定したアルバムの動画データを表示",
     *                 @OA\Items(
     *                      ref="#/components/schemas/video_list"
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
     *                 example="動画を取得出来ませんでした"
     *             )
     *         )
     *     ),
     * )
     * 
     * 動画取得用メソッド
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

            // 動画情報取得(black_list等の取得例: $data['image'][0]['black_list'][3])
            $data = $this->db->searchQueryPaginate($conditions, $order);
            
            return response()->json(['videos' => $data], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            Log::error(config('const.SystemMessage.SYSTEM_ERR').get_class($this).'::'.__FUNCTION__.":".$e->getMessage(). $this->getUserInfo($request));

            return response()->json([
              'error_message' => config('const.UserVideo.GET_ERR'),
              'status'        => 500,
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
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

            // 保存データの設定
            $data = [];
            // データの保存処理(仮保存)
            $userVideo = UserVideo::create([
                'user_id'       => $request->input('user_id'),
                'album_id'      => $request->input('album_id'),
                'type'          => $request->input('type'),
                'image_file'    => config('const.UserVideo.BEFORE_SAVE_NAME'),
                'black_list'    => $blacklist,
                'white_list'    => $whitelist
            ]);
            
            // ファイル名の生成
            $filename = Common::getUniqueFilename($request->file('image_file'), $userVideo->id);
            $data['id'] = $userVideo->id;
            $data['image_file'] = $filename;
            // データの保存処理(正式保存)
            $this->db->save($data);
            
            // // 動画の保存処理
            Common::fileSave($request->file('image_file'), config('const.Aws.MAIN'), $request->input('album_id'), $filename);

            DB::commit();
            return response()->json([
                'info_message' => config('const.UserVideo.REGISTER_INFO'),
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            DB::rollback();
            Log::error(config('const.SystemMessage.SYSTEM_ERR').get_class($this).'::'.__FUNCTION__.":".$e->getMessage(). $this->getUserInfo($request));

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
    public function destroy(Request $request, $group, $album, $video)
    {
        try {
            DB::beginTransaction();

            // データ削除
            $this->db->baseDelete($video);
            
            DB::commit();
            return response()->json(['info_message' => config('const.UserVideo.DELETE_INFO')], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            DB::rollback();
            Log::error(config('const.SystemMessage.SYSTEM_ERR').get_class($this).'::'.__FUNCTION__.":".$e->getMessage(). $this->getUserInfo($request));

            return response()->json([
              'error_message' => config('const.UserVideo.DELETE_ERR'),
              'status'        => 500,
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }
}
