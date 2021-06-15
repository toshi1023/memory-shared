<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

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
}
