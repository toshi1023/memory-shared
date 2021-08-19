<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class News extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    // プライマリキー設定
    protected $primaryKey = ['user_id1', 'user_id2'];
    // increment無効化
    public $incrementing = false;
}
