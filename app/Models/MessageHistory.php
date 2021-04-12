<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MessageHistory extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $guarded = ['id'];

    /**
     * usersテーブルと1対多のリレーション構築(受信者側の設定)
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * usersテーブルと1対多のリレーション構築(送信者側の設定)
     */
    public function own()
    {
        return $this->belongsTo('App\Models\User', 'own_id', 'id');
    }
}
