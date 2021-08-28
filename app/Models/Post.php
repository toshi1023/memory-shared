<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $guarded = ['id'];

    /**
     * post_commentsテーブルと1対多のリレーション構築(1側の設定)
     */
    public function postComments()
    {
        return $this->hasMany('App\Models\PostComment', 'post_id', 'id');
    }
}
