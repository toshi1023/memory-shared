<?php

namespace App\Repositories\News;

use App\Models\News;
use App\Repositories\BaseRepository;

class NewsRepository extends BaseRepository implements NewsRepositoryInterface
{
    protected $model;

    public function __construct(News $model)
    {
        $this->model = $model;
    }

    /**
     * 基本クエリ
     * 引数1: 検索条件, 引数2: ソート条件, 引数3: 削除済みデータの取得フラグ
     */
    public function searchQuery($conditions=[], $order=[], bool $softDelete=false)
    {
        return $this->baseSearchQuery($conditions, $order, $softDelete)
                    ->leftJoin('nread_managements', function ($join) {
                        // nread_managementsテーブルのデータも同時に取得
                        $join->on('news.user_id', '=', 'nread_managements.news_user_id')
                             ->on('news.news_id', '=', 'nread_managements.news_id');
                    })
                    ->select('news.*', 'nread_managements.user_id as read_user_id')
                    ->get();
    }
    
    /**
     * データ保存
     */
    public function save($data, $model=null)
    {
        return $this->baseSave($data, $model);
    }

    /**
     * データ削除
     */
    public function delete($user_id, $news_id)
    {
        $model = $this->baseSearchFirst(['user_id' => $user_id, 'news_id' => $news_id]);
        
        return $model->delete();
    }
}