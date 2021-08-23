<?php

namespace App\Repositories\GroupHistory;

use App\Models\GroupHistory;
use App\Repositories\BaseRepository;
use App\Repositories\News\NewsRepositoryInterface;
use App\Repositories\Group\GroupRepositoryInterface;

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
     * ユーザIDを検索
     * 引数1: 検索条件, 引数2: ソート条件, 引数3: 削除済みデータの取得フラグ
     */
    public function searchUserId($conditions=[], $order=[], bool $softDelete=false)
    {
        return $this->baseSearchQuery($conditions, $order, $softDelete)
                    ->select('user_id')
                    ->get();
    }

    /**
     * グループIDを検索
     * 引数1: 検索条件, 引数2: ソート条件, 引数3: 削除済みデータの取得フラグ
     */
    public function searchGroupId($conditions=[], $order=[], bool $softDelete=false)
    {
        return $this->baseSearchQuery($conditions, $order, $softDelete)
                    ->select('group_id')
                    ->get();
    }

    /**
     * グループ情報を取得(単体)
     * 引数1: 検索条件, 引数2: ソート条件, 引数3: 削除済みデータの取得フラグ
     */
    public function searchGroupFirst($conditions=[], $order=[], bool $softDelete=false)
    {
        $groupRepository = $this->baseGetRepository(GroupRepositoryInterface::class);
        return $groupRepository->baseSearchQuery($conditions, $order, $softDelete)
                               ->first();
    }

    /**
     * データの存在確認
     * 引数1: 検索条件, 引数2: ソート条件, 引数3: 削除済みデータの取得フラグ
     */
    public function searchExists($conditions=[], $order=[], bool $softDelete=false)
    {
        return $this->baseSearchQuery($conditions, $order, $softDelete)->exists();
    }

    /**
     * データ保存
     */
    public function save($data, $model=null)
    {
        return $this->baseSave($data, $model);
    }

    /**
     * グループ申請通知のデータ保存
     * 引数1：ユーザID, 引数2：グループ名, 引数3：申請ステータス
     */
    public function saveGroupInfo($user_id, $group_name, $status)
    {
        $newsRepository = $this->baseGetRepository(NewsRepositoryInterface::class);

        return $newsRepository->saveGroupInfo($user_id, $group_name, $status);
    }

    /**
     * ファミリーIDの取得
     * 引数: 検索条件(group_idを指定)
     */
    public function getFamilies($conditions)
    {
        $query = $this->baseSearchQuery($conditions)
                      ->select('user_id')
                      ->distinct()
                      ->get();

        return $query;
    }

    /**
     * 指定ユーザの参加中グループIDの取得
     * 引数: 検索条件(user_idを指定)
     */
    public function getParticipating($conditions)
    {
        $query = $this->baseSearchQuery($conditions)
                      ->select('group_id')
                      ->get();

        return $query;
    }

    /**
     * データ削除
     * 引数: グループID
     */
    public function delete($group_id)
    {
        // group_historiesテーブルのデータを削除
        $data = $this->baseSearchQuery(['group_id' => $group_id])->select('id')->get();

        foreach($data as $value) {
            $this->baseDelete($value->id);
        }

        return;
    }
}