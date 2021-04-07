<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index(Request $request, User $model)
    {
        $db = $model;
        $data = $db->select()->first();

        return response()->json($data, 200, [], JSON_UNESCAPED_UNICODE);
    }
}
