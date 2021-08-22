<?php

namespace App\Repositories\MreadManagement;

use App\Models\MreadManagement;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

class MreadManagementRepository extends BaseRepository implements MreadManagementRepositoryInterface
{
    protected $model;

    public function __construct(MreadManagement $model)
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
    public function save($data)
    {
        return MreadManagement::create([
            'message_id' => $data['message_id'],
            'own_id'    => $data['own_id'],
            'user_id'    => $data['user_id']
        ]);
    }

    /**
     * データ削除()
     * 引数1：検索条件
     * 引数2：削除対象データのメッセージ
     */
    public function delete($key, $messages)
    {
        foreach($messages as $message) {
            // 検索条件のmessage_idを更新
            $key['message_id'] = $message->message_id;
            
            DB::delete(
                'delete from mread_managements WHERE message_id = ? AND own_id = ? AND user_id = ?', 
                [$key['message_id'], $key['own_id'], $key['user_id']]
            );
        }
        return;
    }
}