<?php

namespace App\Repositories\GroupHistory;

use App\Models\GroupHistory;
use App\Repositories\BaseRepository;
use App\Repositories\Family\FamilyRepositoryInterface;

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
     * データ保存
     */
    public function save($data, $model=null)
    {
        // group_historiesテーブルにデータを保存
        $data = $this->baseSave($data, $model);

        // 申請状況のデータが承認済みの場合、familiesテーブルへの保存処理を実行
        if($data->status === config('const.GroupHistory.APPROVAL')) {
            $familyRepository = $this->baseGetRepository(FamilyRepositoryInterface::class);

            // 保存したグループに所属するユーザIDをすべて取得
            $family = $this->model
                           ->baseSearch([
                               'group_id' => $data->group_id,
                               'status'   => config('const.GroupHistory.APPROVAL')
                            ])
                           ->select('user_id');
            
            // 申請ユーザが属するグループのIDを取得
            $group_id = $this->model
                             ->baseSearch([
                                'user_id' => $data->user_id, 
                                'status'  => config('const.GroupHistory.APPROVAL')
                             ])
                             ->select('group_id');
            
            // familiesテーブルの新規保存処理
            foreach($family as $value) {
                // 対象ユーザと同じグループに属しているか確認
                $exists = $this->model
                               ->baseSearch([
                                   'user_id'     => $value,
                                   'status'      => config('const.GroupHistory.APPROVAL'),
                                   '@ingroup_id' => $group_id->toArray()
                               ])->exists();

                // 属していない場合、新規でfamiliesテーブルに保存
                if(!$exists) {
                    $familyRepository->save(['user_id1' => $data->user_id, 'user_id2' => $value]);
                }
            }
            
        }
        return;
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
}