<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\CanResetPassword;
use App\Notifications\PasswordResetNotification;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasApiTokens, CanResetPassword;

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
     * Override to send for password reset notification.
     *
     * @param [type] $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new PasswordResetNotification($token));
    }

    /**
     * 画像のパスを取得
     */
    public function getImageUrlAttribute()
    {
        // 画像パスを設定
        if($this->image_file) {
            return env('AWS_BUCKET_URL').'/'.config('const.Aws.USER').'/'.$this->id.'/'.$this->image_file;
        }
        return env('AWS_BUCKET_URL').'/no-image.jpg';
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
     * familiesテーブルとリレーション構築
     *   ※フロント画面の"ファミリー"フラグに使用
     */
    public function families1()
    {
        return $this->hasMany('App\Models\Family', 'user_id1', 'id')
                    ->where('user_id2', '=', Auth::user()->id);
    }

    /**
     * familiesテーブルとリレーション構築
     *   ※フロント画面の"ファミリー"フラグに使用
     */
    public function families2()
    {
        return $this->hasMany('App\Models\Family', 'user_id2', 'id')
                    ->where('user_id1', '=', Auth::user()->id);
    }
    
    /**
     * message_relationsテーブルとリレーション構築
     *   ※フロント画面の"トーク中"フラグに使用
     */
    public function message_relations1()
    {
        return $this->hasMany('App\Models\MessageRelation', 'user_id1', 'id')
                    ->where('user_id2', '=', Auth::user()->id);
    }

    /**
     * message_relationsテーブルとリレーション構築
     *   ※フロント画面の"トーク中"フラグに使用
     */
    public function message_relations2()
    {
        return $this->hasMany('App\Models\MessageRelation', 'user_id2', 'id')
                    ->where('user_id1', '=', Auth::user()->id);
    }
    
    /**
     * groupsテーブルと1対多のリレーション構築(1側の設定)
     */
    public function groups()
    {
        return $this->belongsToMany('App\Models\Group', 'group_histories', 'user_id', 'group_id')
                    ->withPivot('status', 'created_at', 'updated_at');
    }

    /**
     * 自身がホストのgroupsテーブルと1対多のリレーション構築(1側の設定)
     */
    public function host_groups()
    {
        return $this->belongsToMany('App\Models\Group', 'host_user_id', 'id')
                    ->where('host_user_id', '=', Auth::user()->id);
    }
    
    /**
     * albumsテーブルと1対多のリレーション構築(1側の設定)
     */
    public function albums()
    {
        return $this->hasMany('App\Models\Album');
    }
}
