<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

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
        return env('AWS_BUCKET_URL').'/no-image.jpg';
    }

    /**
     * usersテーブルと1対多のリレーション構築(多側の設定)
     */
    public function users()
    {
        return $this->belongsToMany('App\Models\User', 'group_histories', 'group_id')
                    ->where('users.id', '=', Auth::user()->id)
                    ->withPivot('status', 'created_at', 'updated_at')
                    ->whereNull('group_histories.deleted_at');
    }

    /**
     * group_historiesテーブルと1対多のリレーション構築(1側の設定)
     */
    public function groupHistories()
    {
        return $this->hasMany('App\Models\GroupHistory', 'group_id', 'id')
                    ->where('group_histories.status', '=', config('const.GroupHistory.APPROVAL'));
    }

    /**
     * albumsテーブルと1対多のリレーション構築(1側の設定)
     */
    public function albums()
    {
        return $this->hasMany('App\Models\Album', 'group_id', 'id');
    }
}
