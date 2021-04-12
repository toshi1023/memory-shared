<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\User\UserRepositoryInterface;

class UserController extends Controller
{

    protected $db;

    public function __construct(UserRepositoryInterface $database)
    {
        $this->db = $database;
    }

    public function index(Request $request)
    {
        $data = $this->db->baseQuery();
        
        return response()->json($data, 200, [], JSON_UNESCAPED_UNICODE);
    }
}
