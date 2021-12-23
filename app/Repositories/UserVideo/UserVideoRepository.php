<?php

namespace App\Repositories\UserVideo;

use App\Models\UserVideo;
use App\Repositories\BaseRepository;
use App\Repositories\UserVideo\UserVideoRepositoryInterface;
use Illuminate\Support\Facades\Auth;

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
     * 選択したアルバム情報と関係する動画を取得
     * 引数1: 検索条件, 引数2: ソート条件, 引数3: 表示件数
     */
    public function searchQueryPaginate($conditions=[], $order=[], int $paginate=10)
    {
        return $this->baseSearchQuery($conditions, $order)
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