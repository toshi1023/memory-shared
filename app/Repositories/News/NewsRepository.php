<?php

namespace App\Repositories\News;

use App\Models\News;
use App\Repositories\BaseRepository;
use App\Repositories\NreadManagement\NreadManagementRepositoryInterface;

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
     * 基本クエリ(単体)
     * 引数1: 検索条件, 引数2: ソート条件, 引数3: 削除済みデータの取得フラグ
     */
    public function searchFirst($conditions=[], $order=[], bool $softDelete=false)
    {
        return $this->baseSearchQuery($conditions, $order, $softDelete)
                    ->leftJoin('nread_managements', function ($join) {
                        // nread_managementsテーブルのデータも同時に取得
                        $join->on('news.user_id', '=', 'nread_managements.news_user_id')
                             ->on('news.news_id', '=', 'nread_managements.news_id');
                    })
                    ->select('news.*', 'nread_managements.user_id as read_user_id')
                    ->first();
    }
    
    /**
     * データ保存
     */
    public function save($data, $model=null)
    {
        return $this->baseSave($data, $model);
    }

    /**
     * グループ申請通知のデータ保存
     * 引数1：ユーザID, 引数2：グループ名, 引数3：申請ステータス
     */
    public function saveGroupInfo($user_id, $group_name, $status)
    {
        $title = $group_name.'の参加申請について';
        $content = '';

        if($status === config('const.GroupHistory.APPLY')) {
            $content = $group_name.'の参加申請が完了しました。申請の結果が出るまでお待ちください。';
        } else {
            $content = $group_name.'の参加が承認されました。Home画面の参加グループ一覧よりご確認ください。';
        }

        $data = [
            'user_id'           => $user_id,
            'news_id'           => $this->getNewsId($user_id),
            'title'             => $title,
            'content'           => $content,
            'update_user_id'    => $user_id
        ];
        $data = $this->baseSave($data);

        // 未読管理テーブルに保存
        $nreadRepository = $this->baseGetRepository(NreadManagementRepositoryInterface::class);

        $nreadData = [
            'news_user_id'  => $data->user_id,
            'news_id'       => $data->news_id,
            'user_id'       => $data->user_id
        ];
        $nreadRepository->save($nreadData);

        return;
    }

    /**
     * データ削除
     */
    public function delete($user_id, $news_id)
    {
        $model = $this->baseSearchFirst(['user_id' => $user_id, 'news_id' => $news_id]);
        
        return $model->delete();
    }

    /**
     * 新規保存用のニュースIDの取得
     * 引数：ユーザID
     */
    public function getNewsId(int $user_id = 0)
    {
        return $this->baseSearchFirst(['user_id' => $user_id], ['news_id' => 'desc'])->news_id + 1;
    }
}