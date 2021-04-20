<?php

namespace App\Repositories\GroupHistory;

use App\Models\GroupHistory;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

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
        return $this->baseSave($data, $model);
    }

    /**
     * 指定グループの参加者一覧を取得
     * 引数1: 検索条件, 引数2: ソート条件, 引数3: 削除済みデータの取得フラグ
     */
    public function getFriends($conditions=[], $order=[], bool $softDelete=false)
    {
        $query = $this->baseSearchQuery($conditions, $order, $softDelete)
                      ->select(DB::raw('distinct(user_id)'), 'group_id', 'status')
                      ->with(['user' => function ($query) {
                          // 会員ユーザを取得
                          $query->select('id', 'name', 'email', 'status')
                                ->where('stauts', '=', config('const.User.MEMBER'));
                      }]);

        return $query->get();
    }
}