<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Repositories\BaseRepository;
use App\Repositories\GroupHistory\GroupHistoryRepositoryInterface;
use App\Repositories\Group\GroupRepositoryInterface;
use App\Lib\Common;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    protected $model;

    public function __construct(User $model)
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
     * 参加中のグループを取得
     * 引数: 検索条件
     */
    public function getGroups($conditions)
    {
        $groupHistoryRepository = $this->baseGetRepository(GroupHistoryRepositoryInterface::class);

        return $groupHistoryRepository->baseSearchQuery($conditions)->select('group_id')->get();
    }

    /**
     * 参加中のグループの参加者を取得
     * 引数1: 検索条件, 引数2: ソート条件, 引数3: 削除済みデータの取得フラグ
     */
    public function getFriends($conditions, $order=[], bool $softDelete=false)
    {
        // フレンドのIDを取得
        $groupHistoryRepository = $this->baseGetRepository(GroupHistoryRepositoryInterface::class);

        $users = $groupHistoryRepository->getFriends($conditions);

        // 取得したフレンドIDを検索条件に設定
        $friends_conditions['@inid'] = Common::setInCondition($users->toArray());
        // $this->modelの値をUserクラスのインスタンスに書き換え
        $this->baseGetModel(User::class);

        return $this->searchQuery($friends_conditions, $order, $softDelete);
    }
}