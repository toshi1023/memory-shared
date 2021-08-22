<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NreadManagement extends Model
{
    use HasFactory;

    protected $fillable = ['news_user_id', 'news_id', 'user_id', 'created_at', 'updated_at'];
    protected $table = 'nread_managements';
    // プライマリキー設定
    protected $primaryKey = ['news_user_id', 'news_id', 'user_id'];
    // increment無効化
    public $incrementing = false;
}
