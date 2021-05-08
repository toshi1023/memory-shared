<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserVideo extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $guarded = ['id'];
    protected $appends = ['video_url'];
    protected $casts = [
        'black_list'  => 'json',
        'white_list'  => 'json',
    ];

    /**
     * 動画のパスを取得
     */
    public function getVideoUrlAttribute()
    {
        // 画像パスを設定
        return env('AWS_BUCKET_URL').'/'.config('const.Aws.MAIN').'/'.$this->album_id.'/'.$this->image_file;
    }

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
