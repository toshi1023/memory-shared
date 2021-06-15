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
    protected $appends = ['image_url']; 

    /**
     * 画像のパスを取得
     */
    public function getImageUrlAttribute()
    {
        // 画像パスを設定
        if($this->image_file) {
            return env('AWS_BUCKET_URL').'/'.config('const.Aws.GROUP').'/'.$this->host_user_id.'/'.$this->image_file;
        }
    }

    /**
     * usersテーブルと1対多のリレーション構築(多側の設定)
     */
    public function users()
    {
        return $this->belongsToMany('App\Models\User', 'group_histories', 'group_id', 'user_id')
                    ->withPivot('status', 'created_at', 'updated_at');
    }
}
