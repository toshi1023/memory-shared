<?php

namespace App\Repositories\MreadManagement;

use App\Models\MreadManagement;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Auth;

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
}