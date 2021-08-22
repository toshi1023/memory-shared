<?php

namespace App\Repositories\NreadManagement;

use App\Models\NreadManagement;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Auth;
use App\Repositories\News\NewsRepositoryInterface;

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
     * 未読フラグ削除後のニュースデータを取得
     * 引数1: 検索条件, 引数2: ソート条件, 引数3: 削除済みデータの取得フラグ
     */
    public function getNewsFirst($conditions=[], $order=[], bool $softDelete=false)
    {
        $newsRepository = $this->baseGetRepository(NewsRepositoryInterface::class);

        return $newsRepository->searchFirst($conditions, $order, $softDelete);
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

    /**
     * データ削除
     */
    public function delete($conditions)
    {
        $model = $this->baseSearchFirst($conditions);
        
        return $model->delete();
    }
}