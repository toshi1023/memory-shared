<?php

namespace App\Repositories\Group;

use App\Models\Group;
use App\Repositories\BaseRepository;
use App\Lib\Common;
use App\Models\GroupHistory;
use App\Repositories\GroupHistory\GroupHistoryRepositoryInterface;

class GroupRepository extends BaseRepository implements GroupRepositoryInterface
{
    protected $model;

    public function __construct(Group $model)
    {
        $this->model = $model;
    }

    /**
     * 基本クエリ
     * 引数1: 検索条件, 引数2: ソート条件, 引数3: 削除済みデータの取得フラグ
     */
    public function searchQuery($conditions=[], $order=[], bool $softDelete=false)
    {
        return $this->baseSearchQuery($conditions, $order, $softDelete)
                    ->with(['users'])
                    ->get();
    }

    public function testfunc()
    {
        $gh = $this->baseGetRepository(GroupHistoryRepositoryInterface::class);
        return $gh->testfunc();
    }
    
    /**
     * データ保存
     */
    public function save($data, $model=null)
    {
        return $this->baseSave($data, $model);
    }

}