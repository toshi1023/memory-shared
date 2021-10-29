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
     * @OA\Schema(
     *     schema="album_list",
     *     required={"id", "name", "group_id", "image_file", "host_user_id", "memo", "update_user_id", "created_at", "updated_at", "deleted_at"},
     *     @OA\Property(property="id", type="integer", example=1),
     *     @OA\Property(property="name", type="string", example="test album 1"),
     *     @OA\Property(property="group_id", type="integer", example="4"),
     *     @OA\Property(property="image_file", type="string", example="xxxxoooo.png"),
     *     @OA\Property(property="host_user_id", type="integer", example="2"),
     *     @OA\Property(property="memo", type="string", example="ここに備考を記載（フロント画面では使用しない）"),
     *     @OA\Property(property="update_user_id", type="integer", example="2"),
     *     @OA\Property(property="created_at", type="string", example="2021-04-25 12:02:55"),
     *     @OA\Property(property="updated_at", type="string", example="2021-04-28 14:13:00"),
     *     @OA\Property(property="deleted_at", type="string", example="null"),
     * )
     */
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
     * @OA\Schema(
     *     schema="album_errors",
     *     required={"name", "image_file", "host_user_id"},
     *     @OA\Property(property="name", type="object", required={"unique", "name.max"},
     *          @OA\Property(property="name.max", type="string", example="アルバム名は50文字以内で入力してください"),
     *     ),
     *     @OA\Property(property="image_file", type="object", required={"mimes", "image_file.max"},
     *          @OA\Property(property="mimes", type="string", example="アップロードファイルはjpeg,png,jpg,gifタイプのみ有効です"),
     *          @OA\Property(property="image_file.max", type="string", example="1Mを超えています。"),
     *     ),
     *     @OA\Property(property="host_user_id", type="object", required={"mimes", "image_file.max"},
     *          @OA\Property(property="GroupMemberRule", type="string", description="アルバムに紐づくグループに加盟しているかどうか確認", example="このグループでアルバムを作成する権限がありません"),
     *          @OA\Property(property="AlbumUpdateRule", type="string", description="アルバム作成者と一致するかどうか確認", example="アルバム作成者以外はアルバム情報を更新できません"),
     *     ),
     * )
     */
    /**
     * @OA\Schema(
     *     schema="album_register",
     *     required={"name", "group_id", "image_file"},
     *     @OA\Property(property="name", type="string", example="test album 12"),
     *     @OA\Property(property="group_id", type="integer", example="4"),
     *     @OA\Property(property="image_file", type="string", example="xxxxoooo.png")
     * )
     */
    /**
     * @OA\Schema(
     *     schema="album_update",
     *     required={"id", "name", "group_id", "image_file"},
     *     @OA\Property(property="id", type="integer", example=8),
     *     @OA\Property(property="name", type="string", example="test album 12"),
     *     @OA\Property(property="group_id", type="integer", example="4"),
     *     @OA\Property(property="image_file", type="string", example="xxxxoooo.png")
     * )
     */


     /**
     * @OA\Get(
     *     path="api/groups/{group}/albums",
     *     description="IDの値が{group}のグループと紐づく、アルバムデータをページネーション形式で取得する(件数：20件)",
     *     produces={"application/json"},
     *     tags={"albums"},
     *     @OA\Parameter(
     *         name="group",
     *         description="グループID",
     *         in="path",
     *         required=true,
     *         type="string"
     *     ),
     *     @OA\Parameter(
     *         name="name@like",
     *         description="アルバム名の検索値(あいまい検索に対応)",
     *         in="query",
     *         required=false,
     *         type="string"
     *     ),
     *     @OA\Parameter(
     *         name="created_at@>equal",
     *         description="アルバムの作成日時で検索（指定日以降に作成されたもので限定）",
     *         in="query",
     *         required=false,
     *         type="string"
     *     ),
     *     @OA\Parameter(
     *         name="created_at@<equal",
     *         description="アルバムの作成日時で検索（指定日以前に作成されたもので限定）",
     *         in="query",
     *         required=false,
     *         type="string"
     *     ),
     *     @OA\Parameter(
     *         name="updated_at@>equal",
     *         description="アルバムの更新日時で検索（指定日以降に更新されたもので限定）",
     *         in="query",
     *         required=false,
     *         type="string"
     *     ),
     *     @OA\Parameter(
     *         name="created_at@<equal",
     *         description="アルバムの更新日時で検索（指定日以前に更新されたもので限定）",
     *         in="query",
     *         required=false,
     *         type="string"
     *     ),
     *     @OA\Parameter(
     *         name="sort_name",
     *         description="アルバム名でソート",
     *         in="query",
     *         required=false,
     *         type="string"
     *     ),
     *     @OA\Parameter(
     *         name="sort_created_at",
     *         description="作成日時順でソート",
     *         in="query",
     *         required=false,
     *         type="string"
     *     ),
     *     @OA\Parameter(
     *         name="sort_updated_at",
     *         description="更新日時順でソート",
     *         in="query",
     *         required=false,
     *         type="string"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success / 指定したグループのアルバムデータを表示",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 description="指定したグループのアルバムデータを表示",
     *                 @OA\Items(
     *                      ref="#/components/schemas/album_list"
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
     *                 example="アルバム情報を取得出来ませんでした"
     *             )
     *         )
     *     ),
     * )
     * 
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
            
            return response()->json(['albums' => $data], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            Log::error(config('const.SystemMessage.SYSTEM_ERR').get_class($this).'::'.__FUNCTION__.":".$e->getMessage());

            return response()->json([
              'error_message' => config('const.Album.GET_ERR'),
              'status'        => 500,
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * @OA\Get(
     *     path="api/groups/{group}/albums/{album}",
     *     description="指定したアルバムの情報とそのアルバムに紐づく画像、動画のデータをすべて取得する",
     *     produces={"application/json"},
     *     tags={"albums"},
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
     *         description="Success / 指定したアルバムとそのアルバムに紐づく画像、動画のデータを表示",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="album",
     *                 type="object",
     *                 description="指定したアルバムデータを表示",
     *                 ref="#/components/schemas/album_list"
     *             ),
     *             @OA\Property(
     *                 property="image",
     *                 type="object",
     *                 description="アルバムに紐づく画像データを表示",
     *                 ref="#/components/schemas/image_list"
     *             ),
     *             @OA\Property(
     *                 property="video",
     *                 type="object",
     *                 description="アルバムに紐づく動画データを表示",
     *                 ref="#/components/schemas/video_list"
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
     *                 example="アルバム情報を取得出来ませんでした"
     *             )
     *         )
     *     ),
     * )
     * 
     * 
     * アルバム詳細の表示用アクション
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
     * @OA\Post(
     *     path="api/groups/{group}/albums/validate",
     *     description="アルバム登録・更新時のバリデーションを実行する",
     *     produces={"application/json"},
     *     tags={"albums"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="request",
     *                 type="object",
     *                 description="リクエストボディのjsonのプロパティの例",
     *                 ref="#/components/schemas/album_register"
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success / バリデーションチェック通過のメッセージをリターン",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="validate_status",
     *                 type="string",
     *                 description="バリデーションチェック通過のメッセージをリターン",
     *                 example="OK"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Server error / バリデーションエラーのメッセージを表示",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="errors",
     *                 type="string",
     *                 description="バリデーションエラーのメッセージを表示",
     *                 ref="#/components/schemas/album_errors"
     *             )
     *         )
     *     ),
     * )
     * 
     * アルバムバリデーション用メソッド
     *   ※データ登録時には非同期処理で常時確認に使用
     */
    public function albumValidate(AlbumRegisterRequest $request)
    {
        return [
            'validate_status' => config('const.SystemMessage.VALIDATE_STATUS')
        ];
    }

    /**
     * @OA\Post(
     *     path="api/groups/{group}/albums",
     *     description="アルバムデータを保存する",
     *     produces={"application/json"},
     *     tags={"albums"},
     *     @OA\Parameter(
     *         name="group",
     *         description="グループID",
     *         in="path",
     *         required=true,
     *         type="string"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="request",
     *                 type="object",
     *                 description="リクエストボディのjsonのプロパティの例",
     *                 ref="#/components/schemas/album_register"
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success / 保存成功のメッセージを表示",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="info_message",
     *                 type="string",
     *                 description="保存成功のメッセージを表示",
     *                 example="アルバム情報を登録しました"
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
     *                 example="アルバム情報の登録に失敗しました"
     *             )
     *         )
     *     ),
     * )
     * 
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
     * @OA\Put(
     *     path="api/groups/{group}/albums/{album}",
     *     description="アルバムデータを更新保存する",
     *     produces={"application/json"},
     *     tags={"albums"},
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
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="request",
     *                 type="object",
     *                 description="リクエストボディのjsonのプロパティの例",
     *                 ref="#/components/schemas/album_update"
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success / 保存成功のメッセージを表示",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="info_message",
     *                 type="string",
     *                 description="保存成功のメッセージを表示",
     *                 example="アルバム情報を登録しました"
     *             ),
     *             @OA\Property(
     *                 property="album",
     *                 type="string",
     *                 description="更新完了後のアルバムデータ",
     *                 ref="#/components/schemas/album_update"
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
     *                 example="アルバム情報の登録に失敗しました"
     *             )
     *         )
     *     ),
     * )
     * 
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
     * @OA\Delete(
     *     path="api/groups/{group}/albums/{album}",
     *     description="グループデータを論理削除する",
     *     produces={"application/json"},
     *     tags={"albums"},
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
     *         description="Success / 論理削除成功のメッセージを表示",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="info_message",
     *                 type="string",
     *                 description="論理削除成功のメッセージを表示",
     *                 example="アルバムの削除が完了しました"
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
     *                 example="サーバーエラーによりアルバムの削除に失敗しました。管理者にお問い合わせください"
     *             )
     *         )
     *     ),
     * )
     * 
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
