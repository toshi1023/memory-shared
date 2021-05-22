<?php

namespace App\Repositories\MessageHistory;

use App\Models\MessageHistory;
use App\Repositories\BaseRepository;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class MessageHistoryRepository extends BaseRepository implements MessageHistoryRepositoryInterface
{
    protected $model;

    public function __construct(MessageHistory $model)
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
     * メッセージ履歴の取得
     */
    public function getMessages($conditions=[], bool $softDelete=false, $paginate=10)
    {
        // own_idがログインユーザのデータを取得
        $anotherQuery = $this->baseSearchQuery($conditions, [], $softDelete);
        
        // 検索条件の設定(user_idがログインユーザ)
        $anotherConditions = [
            'own_id'    => $conditions['user_id'],
            'user_id'   => $conditions['own_id']
        ];
        // user_idがログインユーザのデータを取得
        $query = $this->baseSearchQuery($anotherConditions, [], $softDelete)
                      ->union($anotherQuery)
                      ->orderBy('id', 'asc')
                      ->paginate($paginate);

        return $query;
    }

    /**
     * ユーザ情報の取得
     */
    public function getUser($conditions=[], bool $softDelete=false)
    {
        $userRepository = $this->baseGetRepository(UserRepositoryInterface::class);

        $query = $userRepository->baseSearchFirst($conditions, [], $softDelete);

        return $query;
    }
    
    /**
     * データ保存
     */
    public function save($data, $model=null)
    {
        return $this->baseSave($data, $model);
    }
}