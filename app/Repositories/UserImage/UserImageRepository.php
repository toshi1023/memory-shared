<?php

namespace App\Repositories\UserImage;

use App\Models\UserImage;
use App\Repositories\BaseRepository;
use App\Repositories\UserImage\UserImageRepositoryInterface;

class UserImageRepository extends BaseRepository implements UserImageRepositoryInterface
{
    protected $model;

    public function __construct(UserImage $model)
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
     * 削除用画像データの取得
     * 引数1: グループ名, 引数2: アルバム名, 引数3: 画像名
     */
    public function getDeleteImage($data, $model=null)
    {
        return $this->baseSave($data, $model);
    }
}