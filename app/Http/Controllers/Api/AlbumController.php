<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Album\AlbumRepositoryInterface;
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
     * グループの所有するアルバム一覧
     */
    public function index(Request $request)
    {
        try {
            // dd($request->all());
            // 検索条件
            $conditions = [];
            $conditions['group_id'] = $request->group_id;
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
    
            $data = $this->db->searchQuery($conditions, $order);

            return response()->json($data, 200, [], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            Log::error(config('const.SystemMessage.SYSTEM_ERR').get_class($this).'::'.__FUNCTION__.":".$e->getMessage());

            return response()->json([
              'error_message' => config('const.Album.GET_ERR'),
              'status'        => 500,
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }
}
