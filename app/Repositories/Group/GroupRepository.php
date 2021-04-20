<?php

namespace App\Repositories\Group;

use App\Models\Group;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

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
                      ->select('id')
                      ->with(['users' => function ($query) {
                          // 会員ユーザを取得
                          $query->select('users.id', 'users.name', 'users.email', 'users.status')
                                ->where('users.status', '=', config('const.User.MEMBER'));
                      }]);
        dd($query->toSql());
        return $query->get();
    }
}