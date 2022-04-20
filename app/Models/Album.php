<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Album extends Model
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
            return config('const.Aws.URL').'/'.config('const.Aws.ALBUM').'/'.$this->id.'/'.$this->image_file;
        }
        return config('const.Aws.URL').'/no-image.jpg';
    }

    /**
     * usersテーブルと1対多のリレーション構築(多側の設定)
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * user_imagesテーブルと1対多のリレーション構築(1側の設定)
     */
    public function userImages()
    {
        return $this->hasMany('App\Models\UserImage');
    }

    /**
     * user_videosテーブルと1対多のリレーション構築(1側の設定)
     */
    public function userVideos()
    {
        return $this->hasMany('App\Models\UserVideo');
    }
}
