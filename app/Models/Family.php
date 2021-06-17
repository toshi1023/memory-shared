<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Family extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $guarded = ['id'];
    // プライマリキー設定
    protected $primaryKey = ['user_id1', 'user_id2'];
    // increment無効化
    public $incrementing = false;

    /**
     * usersテーブルと1対多のリレーション構築(多側の設定)
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
