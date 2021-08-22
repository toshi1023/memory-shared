<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MreadManagement extends Model
{
    use HasFactory;

    protected $fillable = ['message_id', 'own_id', 'user_id', 'created_at', 'updated_at'];
    protected $table = 'mread_managements';
    // プライマリキー設定
    protected $primaryKey = ['message_id', 'user_id'];
    // increment無効化
    public $incrementing = false;

    /**
     * message_historiesテーブルと1対多のリレーション構築(1側の設定)
     */
    public function messageHistory()
    {
        return $this->belongsTo('App\Models\MessageHistory', 'id', 'message_id');
    }
}
