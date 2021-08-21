<?php

namespace App\Repositories\NreadManagement;

use App\Models\NreadManagement;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Auth;

class NreadManagementRepository extends BaseRepository implements NreadManagementRepositoryInterface
{
    protected $model;

    public function __construct(NreadManagement $model)
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
        return NreadManagement::create([
            'news_user_id' => $data['news_user_id'],
            'news_id'      => $data['news_id'],
            'user_id'      => $data['user_id']
        ]);
    }
}