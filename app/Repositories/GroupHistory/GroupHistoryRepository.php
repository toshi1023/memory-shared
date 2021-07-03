<?php

namespace App\Repositories\GroupHistory;

use App\Models\GroupHistory;
use App\Repositories\BaseRepository;

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
     * グループID
     * 引数1: 検索条件, 引数2: ソート条件, 引数3: 削除済みデータの取得フラグ
     */
    public function searchGroupId($conditions=[], $order=[], bool $softDelete=false)
    {
        return $this->baseSearchQuery($conditions, $order, $softDelete)
                    ->select('group_id')
                    ->get();
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
     * フレンドIDの取得
     * 引数: 検索条件(group_idを指定)
     */
    public function getFriends($conditions)
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
            $this->baseDelete($value);
        }

        return;
    }
}