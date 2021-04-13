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
        if($request->name) $conditions = Common::setConditions($request);
        
        // ソート条件
        $order = [];
        
        $data = $this->db->baseSearchQuery($conditions, $order)->get();
        
        return response()->json($data, 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * 【ハンバーガーメニュー】
     * ユーザ詳細の表示用アクション
     */
    public function show(Request $request, $user)
    {
        // 検索条件の設定
        $conditions = [
            'name' => $user
        ];
        
        $data = $this->db->baseSearchQuery($conditions)->first();

        return response()->json($data, 200, [], JSON_UNESCAPED_UNICODE);
    }
}
