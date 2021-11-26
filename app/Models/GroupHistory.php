<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class GroupHistory extends Pivot
{
    use HasFactory, SoftDeletes;

    protected $table = 'group_histories';
    protected $dates = ['deleted_at'];
    protected $guarded = ['id'];

    /**
     * statusの日本語名を取得
     */
    public function getStatusTypeAttribute()
    {
        // 申請中
        if($this->status === config('const.GroupHistory.APPLY')) {
            return config('const.GroupHistory.APPLY_WORD');
        }
        // 承認済み
        if($this->status === config('const.GroupHistory.APPROVAL')) {
            return config('const.GroupHistory.APPROVAL_WORD');
        }
    }

    /**
     * groupsテーブルと1対1のリレーション構築
     */
    public function group()
    {
        return $this->belongsTo('App\Models\Group', 'group_id', 'id');
    }

    /**
     * usersテーブルと1対1のリレーション構築
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
}
