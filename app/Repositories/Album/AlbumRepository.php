<?php

namespace App\Repositories\Album;

use App\Models\Album;
use App\Repositories\BaseRepository;
use App\Repositories\UserImage\UserImageRepositoryInterface;
use App\Repositories\UserVideo\UserVideoRepositoryInterface;

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
     * 選択したアルバム情報と関係するイメージを取得
     * 引数1: 検索条件, 引数2: 表示件数
     */
    public function getImages($conditions, int $paginate=30)
    {
        $userImageRepository = $this->baseGetRepository(UserImageRepositoryInterface::class);
        $query = $userImageRepository->baseSearchQueryPaginate($conditions, [], $paginate);
        
        return $query;
    }

    /**
     * 選択したアルバム情報と関係する動画を取得
     * 引数1: 検索条件, 引数2: 表示件数
     */
    public function getVideos($conditions, int $paginate=15)
    {
        $userVideoRepository = $this->baseGetRepository(UserVideoRepositoryInterface::class);
        $query = $userVideoRepository->baseSearchQueryPaginate($conditions, [], $paginate);
        
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