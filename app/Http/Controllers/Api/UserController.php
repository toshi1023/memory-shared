<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\User\UserRepositoryInterface;
use App\Http\Requests\UserRegisterRequest;
use App\Lib\Common;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{

    protected $db;

    public function __construct(UserRepositoryInterface $database)
    {
        $this->db = $database;
    }

    /**
     * 【ハンバーガーメニュー】
     * ユーザ一覧の表示用アクション
     */
    public function index(Request $request)
    {
        try {
            // 検索条件
            $conditions = [];
            if($request->email || $request->input('name@like')) $conditions = Common::setConditions($request);
            
            // ソート条件
            $order = [];
            if($request->sort_name || $request->sort_id) $order = Common::setOrder($request);
    
            $data = $this->db->searchQuery($conditions, $order);
            
            return response()->json($data, 200, [], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            Log::error(config('const.SystemMessage.SYSTEM_ERR').get_class($this).'::'.__FUNCTION__.":".$e->getMessage());

            return response()->json([
              'error_message' => config('const.User.GET_ERR'),
              'status'        => 500,
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * 【ハンバーガーメニュー】
     * ユーザ詳細の表示用アクション
     *   ※$user: nameカラムの値を設定する
     */
    public function show(Request $request, $user)
    {
        try {
            // 検索条件の設定
            $conditions = [
                'name' => $user
            ];
            
            $data = $this->db->searchQuery($conditions);
    
            return response()->json($data, 200, [], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            Log::error(config('const.SystemMessage.SYSTEM_ERR').get_class($this).'::'.__FUNCTION__.":".$e->getMessage());

            return response()->json([
              'error_message' => config('const.User.GET_ERR'),
              'status'        => 500,
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * ユーザバリデーション用メソッド
     *   ※データ登録時には非同期処理で常時確認に使用
     */
    public function userValidate(UserRegisterRequest $request)
    {
        return;
    }

    /**
     * ユーザ登録処理用アクション
     */
    public function store(UserRegisterRequest $request)
    {
        DB::beginTransaction();
        try {
            // ファイル名の生成
            $filename = null;
            if ($request->file('image_file')){
              $filename = Common::getFilename($request->file('image_file'));
            }
    
            $data = $request->all();
            $data['image_file'] = $filename;
    
            // データの保存処理
            $this->db->save($data);

            // ファイルの保存処理
            if($request->file('image_file')) {
                Common::fileSave($request->file('image_file'), config('const.Aws.USER'), $request->name, $filename);
            }

            DB::commit();
            return response()->json([
                'info_message' => config('const.User.REGISTER_INFO'),
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            DB::rollback();
            Log::error(config('const.SystemMessage.SYSTEM_ERR').get_class($this).'::'.__FUNCTION__.":".$e->getMessage());

            // 作成失敗時はエラーメッセージを返す
            return response()->json([
              'error_message' => config('const.User.REGISTER_ERR'),
              'status'        => 500,
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * ユーザ更新処理用アクション
     */
    public function update(UserRegisterRequest $request)
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
            $this->db->save($data);

            // ファイルの保存処理
            if($request->file('image_file')) {
                Common::fileSave($request->file('image_file'), config('const.Aws.USER'), $request->name, $filename);
            }

            DB::commit();
            return response()->json([
                'info_message' => config('const.User.REGISTER_INFO'),
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            DB::rollback();
            Log::error(config('const.SystemMessage.SYSTEM_ERR').get_class($this).'::'.__FUNCTION__.":".$e->getMessage());

            // 作成失敗時はエラーメッセージを返す
            return response()->json([
              'error_message' => config('const.User.REGISTER_ERR'),
              'status'        => 500,
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * 【navメニュー】
     * 同じグループに参加中のユーザ一覧
     */
    public function friends(Request $request)
    {
        try {
            // 検索条件
            $mygroup_conditions = [
                'user_id' => Auth::user()->id,
                'status'  => config('const.GroupHistory.APPROVAL')
            ];

            $groups = $this->db->getGroups($mygroup_conditions);

            $data = [];
            foreach($groups as $key => $value) {
                // 検索条件
                $friends_conditions = [
                    $key => $value
                ];

                $friends = $this->db->getFriends($friends_conditions);
            }
            
            // ソート条件
            $order = [];
            if($request->sort_name || $request->sort_id) $order = Common::setOrder($request);
    
            $data = $this->db->searchQuery($conditions, $order);
            
            return response()->json($data, 200, [], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            Log::error(config('const.SystemMessage.SYSTEM_ERR').get_class($this).'::'.__FUNCTION__.":".$e->getMessage());

            return response()->json([
              'error_message' => config('const.User.GET_ERR'),
              'status'        => 500,
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }
}
