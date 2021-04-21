<?php

namespace App\Repositories\GroupHistory;

use App\Models\GroupHistory;
use App\Repositories\BaseRepository;

class GroupHistoryRepository extends BaseRepository implements GroupHistoryRepositoryInterface
{
    protected $model;

    public function __construct(GroupHistory $model)
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

    /**
     * フレンドIDの取得
     * 引数: 検索条件
     */
    public function getFriends($conditions)
    {
        $query = $this->baseSearchQuery($conditions)
                      ->select('user_id')
                      ->distinct()
                      ->get();

        return $query;
    }
}