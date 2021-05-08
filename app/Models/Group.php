<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Repositories\User\UserRepositoryInterface;

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
        // user情報の取得
        $userRepository = app()->make(UserRepositoryInterface::class);

        $user = $userRepository->baseSearchFirst(['id' => $this->host_user_id]);

        // 画像パスを設定
        if($this->image_file) {
            return env('AWS_BUCKET_URL').'/'.config('const.Aws.GROUP').'/'.$user->name.'/'.$this->image_file;
        }
    }

    /**
     * usersテーブルと1対多のリレーション構築(多側の設定)
     */
    public function users()
    {
        return $this->belongsToMany('App\Models\User', 'group_histories', 'group_id', 'user_id');
    }
}
