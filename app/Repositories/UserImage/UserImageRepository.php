<?php

namespace App\Repositories\UserImage;

use App\Models\UserImage;
use App\Repositories\BaseRepository;
use App\Repositories\UserImage\UserImageRepositoryInterface;
use Illuminate\Support\Facades\Auth;

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
     * 選択したアルバム情報と関係するイメージを取得
     * 引数1: 検索条件, 引数2: ソート条件, 引数3: 表示件数
     */
    public function searchQueryPaginate($conditions=[], $order=[], int $paginate=30)
    {
        return $this->baseSearchQuery($conditions)
                    ->where('black_list', '=', null)
                    ->where('white_list', '=', null)
                    ->orWhere('black_list->'.Auth::user()->id, '!=', Auth::user()->id)
                    ->orWhere('white_list->'.Auth::user()->id, '=', Auth::user()->id)
                    ->paginate($paginate);
        
    }
    
    /**
     * データ保存
     */
    public function save($data, $model=null)
    {
        return $this->baseSave($data, $model);
    }
}