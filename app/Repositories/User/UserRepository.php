<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Repositories\BaseRepository;
use App\Repositories\GroupHistory\GroupHistoryRepositoryInterface;
use App\Repositories\Group\GroupRepositoryInterface;
use App\Repositories\MessageHistory\MessageHistoryRepositoryInterface;
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
        $user_id = 1;
        return $this->baseSearchQuery($conditions, $order, $softDelete)
                    ->select('id', 'name', 'email', 'status', 'image_file')
                    ->with(['families1', 'families2', 'message_relations1', 'message_relations2'])
                    ->get();
    }

    /**
     * 1件のみ取得
     * 引数1: 検索条件, 引数2: ソート条件, 引数3: 削除済みデータの取得フラグ
     */
    public function searchFirst($conditions=[], $order=[], bool $softDelete=false)
    {
        return $this->baseSearchQuery($conditions, $order, $softDelete)
                    ->select('id', 'name', 'email', 'status', 'image_file')
                    ->first();
    }

    /**
     * 表示件数を限定
     * 引数1: 検索条件, 引数2: ソート条件, 引数3: 表示件数
     */
    public function searchQueryLimit($conditions=[], $order=[], int $limit=10)
    {
        return $this->baseSearchQuery($conditions, $order, false)
                    ->select('id', 'name', 'email', 'status', 'image_file')            
                    ->limit($limit);
    }

    /**
     * ページネーションを設定
     * 引数1: 検索条件, 引数2: ソート条件, 引数3: 表示件数
     */
    public function searchQueryPaginate($conditions=[], $order=[], int $paginate=10)
    {
        return $this->baseSearchQuery($conditions, $order, false)
                    ->select('id', 'name', 'email', 'status', 'image_file')
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
     * ワンタイムパスワードを保存
     * 引数：ワンタイムパスワード
     */
    public function saveOnePass($onetime_password, $user_id)
    {
        $data = [];
        $data['id'] = $user_id;
        $data['onetime_password'] = $onetime_password;
        
        return $this->baseSave($data, null);
    }

    /**
     * 参加中のグループ履歴を取得
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
    public function getFamilies($conditions, $order=[], bool $softDelete=false)
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

    /**
     * 参加中グループの取得
     * 引数1: 検索条件, 引数2: ソート条件, 引数3: 削除済みデータの取得フラグ
     */
    public function getParticipating($conditions, $order=[], bool $softDelete=false)
    {
        // フレンドのIDを取得
        $groupHistoryRepository = $this->baseGetRepository(GroupHistoryRepositoryInterface::class);

        $groups = $groupHistoryRepository->getParticipating($conditions);

        // 取得したグループIDを検索条件に設定
        $groups_conditions['@inid'] = Common::setInCondition($groups->toArray());
        // グループの取得
        $groupRepository = $this->baseGetRepository(GroupRepositoryInterface::class);

        return $groupRepository->searchQuery($groups_conditions, $order, $softDelete);
    }

    /**
     * トーク一覧の取得
     * 引数: ユーザID
     */
    public function getMessageList($user_id)
    {
        // トーク一覧を取得
        $messageRepository = $this->baseGetRepository(MessageHistoryRepositoryInterface::class);

        return $messageRepository->getMessageList($user_id);
    }
}