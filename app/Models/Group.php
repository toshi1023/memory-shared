<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $guarded = ['id'];

    /**
     * usersテーブルと1対多のリレーション構築(多側の設定)
     */
    public function users()
    {
        return $this->belongsToMany('App\Models\User', 'group_histories', 'id', 'user_id');
    }
}
