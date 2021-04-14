<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\User\UserRepositoryInterface;
use App\Lib\Common;

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
        // 検索条件
        $conditions = [];
        if($request->email || $request->name) $conditions = Common::setConditions($request);
        
        // ソート条件
        $order = [];
        if($request->sort_name || $request->sort_id) $order = Common::setOrder($request);

        $data = $this->db->searchQuery($conditions, $order);
        
        return response()->json($data, 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * 【ハンバーガーメニュー】
     * ユーザ詳細の表示用アクション
     *   ※$user: nameカラムの値を設定する
     */
    public function show(Request $request, $user)
    {
        // 検索条件の設定
        $conditions = [
            'name' => $user
        ];
        
        $data = $this->db->searchQuery($conditions);

        return response()->json($data, 200, [], JSON_UNESCAPED_UNICODE);
    }
}
