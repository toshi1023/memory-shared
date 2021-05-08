<?php

namespace App\Repositories\UserVideo;

use App\Models\UserVideo;
use App\Repositories\BaseRepository;
use App\Repositories\UserVideo\UserVideoRepositoryInterface;

class UserVideoRepository extends BaseRepository implements UserVideoRepositoryInterface
{
    protected $model;

    public function __construct(UserVideo $model)
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