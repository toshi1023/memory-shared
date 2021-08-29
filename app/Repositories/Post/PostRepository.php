<?php

namespace App\Repositories\Post;

use App\Models\Post;
use App\Repositories\BaseRepository;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\Group\GroupRepositoryInterface;

class PostRepository extends BaseRepository implements PostRepositoryInterface
{
    protected $model;

    public function __construct(Post $model)
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
                    ->with(['postComments'])
                    ->get();
    }

    /**
     * 基本クエリ(単体)
     * 引数1: 検索条件, 引数2: ソート条件, 引数3: 削除済みデータの取得フラグ
     */
    public function searchFirst($conditions=[], $order=[], bool $softDelete=false)
    {
        return $this->baseSearchQuery($conditions, $order, $softDelete)
                    ->with(['postComments'])
                    ->first();
    }
    
    /**
     * データ保存
     */
    public function save($data, $model=null)
    {
        return $this->baseSave($data, $model);
    }

    /**
     * 投稿通知のデータ保存
     * 引数1：ユーザID, 引数2：グループ名, 引数3：申請ステータス
     */
    public function savePostInfo($user_id, $user_name, $group_name)
    {
        $newsRepository = $this->baseGetRepository(NewsRepositoryInterface::class);

        return $newsRepository->savePostInfo($user_id, $user_name, $group_name);
    }

    /**
     * グループ情報の取得
     * 引数：グループID
     */
    public function getGroupInfo($group_id)
    {
        $groupRepository = $this->baseGetRepository(GroupRepositoryInterface::class);

        return $groupRepository->baseSearchFirst(['id' => $group_id]);
    }
}