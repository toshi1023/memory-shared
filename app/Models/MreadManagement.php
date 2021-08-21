<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MreadManagement extends Model
{
    use HasFactory;

    protected $table = 'mread_managements';
    // プライマリキー設定
    protected $primaryKey = ['message_id', 'user_id'];
    // increment無効化
    public $incrementing = false;
}
