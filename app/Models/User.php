<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasApiTokens;

    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * アクセサ許可リスト
     */
    protected $appends = ['image_url']; 

    /**
     * 画像のパスを取得
     */
    public function getImageUrlAttribute()
    {
        // 画像パスを設定
        if($this->image_file) {
            return env('AWS_BUCKET_URL').'/'.config('const.Aws.USER').'/'.$this->name.'/'.$this->image_file;
        }
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

    /**
     * image_commentsテーブルと1対多のリレーション構築(1側の設定)
     */
    public function imageComments()
    {
        return $this->hasMany('App\Models\ImageComment');
    }
    
    /**
     * video_commentsテーブルと1対多のリレーション構築(1側の設定)
     */
    public function videoComments()
    {
        return $this->hasMany('App\Models\VideoComment');
    }
    
    /**
     * groupsテーブルと1対多のリレーション構築(1側の設定)
     */
    public function Groups()
    {
        return $this->belongsToMany('App\Models\Group', 'group_histories', 'user_id', 'group_id');
    }
    
    /**
     * albumsテーブルと1対多のリレーション構築(1側の設定)
     */
    public function Albums()
    {
        return $this->hasMany('App\Models\Album');
    }
}
