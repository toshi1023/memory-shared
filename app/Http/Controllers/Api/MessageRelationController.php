<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\MessageRelation\MessageRelationRepositoryInterface;
use Illuminate\Http\Request;

class MessageRelationController extends Controller
{
    protected $db;

    public function __construct(MessageRelationRepositoryInterface $database)
    {
        $this->db = $database;
    }
}
