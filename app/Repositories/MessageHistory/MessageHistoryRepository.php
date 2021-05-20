<?php

namespace App\Repositories\MessageHistory;

use App\Models\MessageHistory;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Auth;

class MessageHistoryRepository extends BaseRepository implements MessageHistoryRepositoryInterface
{
    protected $model;

    public function __construct(MessageHistory $model)
    {
        $this->model = $model;
    }

    /**
     * 基本クエリ
     * 引数1: 検索条件, 引数2: ソート条件, 引数3: 削除済みデータの取得フラグ
     */
    public function searchQuery($conditions=[], $order=[], bool $softDelete=false)
    {
        return $this->baseSearchQuery($conditions, $order, $softDelete)->get();
    }
    
    /**
     * データ保存
     */
    public function save($data, $model=null)
    {
        return $this->baseSave($data, $model);
    }
}