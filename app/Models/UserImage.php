<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserImage extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $guarded = ['id'];

    /**
     * usersテーブルと1対多のリレーション構築(多側の設定)
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * albumsテーブルと1対多のリレーション構築(多側の設定)
     */
    public function album()
    {
        return $this->belongsTo('App\Models\Album');
    }
}
