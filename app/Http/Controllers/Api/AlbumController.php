<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AlbumController extends Controller
{
    public function index()
    {
        return http_response_code(200);
    }
}