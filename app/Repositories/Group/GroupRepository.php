<?php

namespace App\Repositories\Group;

use App\Models\Group;
use App\Repositories\BaseRepository;
use App\Lib\Common;
use App\Models\GroupHistory;
use App\Repositories\GroupHistory\GroupHistoryRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;

class GroupRepository extends BaseRepository implements GroupRepositoryInterface
{
    protected $model;

    public function __construct(Group $model)
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
                    ->with(['users:id,name,image_file,gender'])
                    ->get();
    }

    /**
     * 単体データ取得
     * 引数1: 検索条件, 引数2: ソート条件, 引数3: 削除済みデータの取得フラグ
     */
    public function searchFirst($conditions=[], $order=[], bool $softDelete=false)
    {
        return $this->baseSearchQuery($conditions, $order, $softDelete)
                    ->with(['users:id,name,image_file,gender', 'groupHistories:id,group_id,user_id,status'])
                    ->first();
    }

    /**
     * ページネーションを設定
     * 引数1: 検索条件, 引数2: ソート条件, 引数3: ページネーション件数
     */
    public function searchQueryPaginate($conditions=[], $order=[], int $paginate=20)
    {
        return $this->baseSearchQuery($conditions, $order, false)
                    ->with(['users:id,name,image_file,gender'])
                    ->paginate($paginate);
    }
    
    /**
     * データ保存
     */
    public function save($data, $model=null)
    {
        return $this->baseSave($data, $model);
    }

    /**
     * データ削除
     */
    public function delete($id)
    {
        // groupsテーブルのデータ削除
        $this->baseDelete($id);

        // group_historiesテーブルのデータを削除
        $groupHistoryRepository = $this->baseGetRepository(GroupHistoryRepositoryInterface::class);
        
        $groupHistoryRepository->delete($id);

        return;
    }

    /**
     * グループの参加者IDを取得
     * 引数1: 検索条件, 引数2: ソート条件, 引数3: 削除済みデータの取得フラグ
     */
    public function getParticipants($conditions, $order=[], bool $softDelete=false)
    {
        // グループ参加者を取得
        $groupHistoryRepository = $this->baseGetRepository(GroupHistoryRepositoryInterface::class);

        return $groupHistoryRepository->baseSearchQuery($conditions, $order, $softDelete)->select('user_id')->get();
    }

    /**
     * ユーザ情報を取得
     * 引数1: 検索条件, 引数2: ソート条件, 引数3: 削除済みデータの取得フラグ
     */
    public function getUsersInfo($conditions, $order=[], int $paginate = 15)
    {
        // グループ参加者を取得
        $userRepository = $this->baseGetRepository(UserRepositoryInterface::class);

        return $userRepository->baseSearchQuery($conditions, $order, false)
                              ->select('id', 'name', 'image_file')
                              ->paginate($paginate);
    }
}