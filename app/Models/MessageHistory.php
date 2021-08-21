<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MessageHistory extends Model
{
    use HasFactory;

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

    /**
     * usersテーブルと1対多のリレーション構築(トーク一覧表示時の設定)
     */
    public function other()
    {
        return $this->belongsTo('App\Models\User', 'otherid', 'id');
    }

    /**
     * mread_managementsテーブルと1対多のリレーション構築(多側の設定)
     */
    public function mreadManagements()
    {
        return $this->hasMany('App\Models\MreadManagement', 'message_id');
    }
}
