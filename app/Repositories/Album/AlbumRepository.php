<?php

namespace App\Repositories\Album;

use App\Models\Album;
use App\Repositories\BaseRepository;
use App\Repositories\Group\GroupRepositoryInterface;
use App\Repositories\UserImage\UserImageRepositoryInterface;
use App\Repositories\UserVideo\UserVideoRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class AlbumRepository extends BaseRepository implements AlbumRepositoryInterface
{
    protected $model;

    public function __construct(Album $model)
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
     * 選択したグループ情報を取得
     * 引数1: 検索条件
     */
    public function getGroup($conditions)
    {
        $groupRepository = $this->baseGetRepository(GroupRepositoryInterface::class);
        $query = $groupRepository->baseSearchFirst($conditions);
        
        return $query;
    }
    
    /**
     * データ保存
     */
    public function save($data, $model=null)
    {
        return $this->baseSave($data, $model);
    }
}