<?php

namespace App\Repositories\MessageHistory;

use App\Models\MessageHistory;
use App\Repositories\BaseRepository;
use App\Repositories\MessageRelation\MessageRelationRepositoryInterface;
use App\Repositories\MreadManagement\MreadManagementRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
        return $this->baseSearchQuery($conditions, $order, $softDelete)->whereNull('deleted_at')->get();
    }

    /**
     * トーク履歴の取得
     * 引数1: 検索条件, 引数2: 削除済みデータの取得フラグ, 引数3: ページネーション件数
     */
    public function getMessages($conditions=[], bool $softDelete=false, $paginate=20)
    {
        // own_idがログインユーザのデータを取得
        $anotherQuery = $this->baseSearchQuery($conditions, [], $softDelete)->whereNull('deleted_at');
        
        // 検索条件の設定(user_idがログインユーザ)
        $anotherConditions = [
            'own_id'    => $conditions['user_id'],
            'user_id'   => $conditions['own_id']
        ];
        // user_idがログインユーザのデータを取得
        $query = $this->baseSearchQuery($anotherConditions, [], $softDelete)
                      ->union($anotherQuery)
                      ->with(['own:id,name,image_file'])
                      ->whereNull('deleted_at')
                      ->orderBy('id', 'desc')
                      ->paginate($paginate);

        return $query;
    }

    /**
     * トーク履歴の取得(単数)
     * 引数1: 検索条件, 引数2: 削除済みデータの取得フラグ, 引数3: ソフトデリート
     */
    public function getMessage($conditions=[], bool $softDelete=false)
    {
        // own_idがログインユーザのデータを取得
        $query = $this->baseSearchQuery($conditions, [], $softDelete)
                      ->with(['own:id,name,image_file'])
                      ->whereNull('deleted_at')
                      ->first();

        return $query;
    }

    /**
     * ログインユーザのトーク一覧を取得
     * 引数1： ユーザID, 引数2: ページネーション件数
     */
    public function getMessageList($user_id, int $paginate = 15)
    {
        // messagesテーブルの値をUnion結合して取得
        $subQuery = $this->baseSearchQuery(['own_id' => $user_id])
                         ->whereNull('message_histories.deleted_at')
                         ->select('*', 'user_id as otherid');

        $subQuery = $this->baseSearchQuery(['user_id' => $user_id])
                         ->whereNull('message_histories.deleted_at')
                         ->select('*', 'own_id as otherid')
                         ->union($subQuery)
                         ->orderBy('updated_at', 'desc');
        
        // 各ユーザごとにメッセージの重複を排除して、最新のトークデータを取得
        $query = $this->model->selectRaw('distinct(messangers.otherid)')
                             ->addSelect(DB::raw('max(messangers.id) AS messangers_id'))
                             ->fromSub($subQuery, 'messangers')
                             ->whereNull('messangers.deleted_at')
                             ->groupByRaw('messangers.otherid');

        // mread_managementsテーブルのデータをカウントで取得
        $subQuery = $this->model->selectRaw('count(mread_managements.own_id) AS mcount')
                                ->addSelect('mread_managements.own_id')
                                ->from('mread_managements')
                                ->where('mread_managements.user_id', '=', $user_id)
                                ->groupByRaw('mread_managements.own_id');
                             
        // messagesテーブルとusersテーブルの内容を結合してログインユーザのメッセージ一覧情報を取得
        $query = $this->model->select('message_histories.*', 'messangers.otherid', 'mread_managements.mcount')
                             ->rightJoinSub($query, 'messangers', 'message_histories.id', '=', 'messangers.messangers_id')
                             ->leftJoinSub($subQuery, 'mread_managements', 'messangers.otherid', '=', 'mread_managements.own_id')
                             ->whereNull('message_histories.deleted_at')
                             ->with(['other:id,name,image_file'])
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
        // message_relationsテーブルに"トーク中"フラグのデータがあるかどうか確認
        $messageRelationRepository = $this->baseGetRepository(MessageRelationRepositoryInterface::class);
        
        $mr_data = [
            'user_id1'    => $data['user_id'],
            'user_id2'    => $data['own_id']
        ];
        $exists1 = $messageRelationRepository->baseSearchQuery($mr_data)->exists();

        $mr_data = [
            'user_id1'    => $data['own_id'],
            'user_id2'    => $data['user_id']
        ];      
        $exists2 = $messageRelationRepository->baseSearchQuery($mr_data)->exists();
        
        // データが無ければ、message_relationsテーブルに"トーク中"フラグのデータを新規保存
        if(!$exists1 && !$exists2) {
            $messageRelationRepository->save($mr_data);
        }

        // message_historiesテーブルにメッセージデータを保存
        return $this->baseSave($data, $model);
    }

    /**
     * 未読管理テーブルにデータ保存
     * 引数：メッセージ履歴データ
     */
    public function saveMread($data)
    {
        $mrmRepository = $this->baseGetRepository(MreadManagementRepositoryInterface::class);

        // データ生成
        $mrm_data = [
            'message_id' => $data['id'],
            'own_id'     => $data['own_id'],
            'user_id'    => $data['user_id']
        ];

        // mread_managementsテーブルに保存
        return $mrmRepository->save($mrm_data);
    }

    /**
     * データ削除(論理削除)
     */
    public function delete($id, $user_id)
    {
        $model = MessageHistory::find($id);

        $model->deleted_at = new Carbon('now', 'Asia/Tokyo');
        $model->update_user_id = $user_id;
        
        return $model->save();
    }
}