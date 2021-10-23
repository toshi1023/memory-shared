<?php

namespace App\Repositories\PostComment;

use App\Models\PostComment;
use App\Repositories\BaseRepository;
use App\Repositories\NreadManagement\NreadManagementRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;

class PostCommentRepository extends BaseRepository implements PostCommentRepositoryInterface
{
    protected $model;

    public function __construct(PostComment $model)
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
                    ->with(['user:id,name,image_file'])
                    ->get();
    }

    /**
     * 基本クエリ(単体)
     * 引数1: 検索条件, 引数2: ソート条件, 引数3: 削除済みデータの取得フラグ
     */
    public function searchFirst($conditions=[], $order=[], bool $softDelete=false)
    {
        return $this->baseSearchQuery($conditions, $order, $softDelete)
                    ->with(['user:id,name,image_file'])
                    ->first();
    }

    /**
     * ページネーションを設定
     * 引数1: 検索条件, 引数2: ソート条件, 引数3: 表示件数
     */
    public function searchQueryPaginate($conditions=[], $order=[], int $paginate=10)
    {
        return $this->baseSearchQuery($conditions, $order, false)
                    ->with(['user:id,name,image_file'])
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