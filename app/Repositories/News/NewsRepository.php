<?php

namespace App\Repositories\News;

use App\Models\News;
use App\Repositories\BaseRepository;
use App\Repositories\NreadManagement\NreadManagementRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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
                             ->on('news.news_id', '=', 'nread_managements.news_id')
                             ->where('nread_managements.user_id', '=', Auth::user()->id);
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
                             ->on('news.news_id', '=', 'nread_managements.news_id')
                             ->where('nread_managements.user_id', '=', Auth::user()->id);
                    })
                    ->select('news.*', 'nread_managements.user_id as read_user_id')
                    ->first();
    }

    /**
     * ページネーションを設定
     * 引数1: 検索条件, 引数2: ソート条件, 引数3: 表示件数
     */
    public function searchQueryPaginate($conditions=[], $order=[], int $paginate=15)
    {
        return $this->baseSearchQuery($conditions, $order, false)
                    ->leftJoin('nread_managements', function ($join) {
                        // nread_managementsテーブルのデータも同時に取得
                        $join->on('news.user_id', '=', 'nread_managements.news_user_id')
                             ->on('news.news_id', '=', 'nread_managements.news_id')
                             ->where('nread_managements.user_id', '=', Auth::user()->id);
                    })
                    ->select('news.*', 'nread_managements.user_id as read_user_id')
                    ->paginate($paginate);
    }
    
    /**
     * データ保存
     */
    public function save($data)
    {
        return News::create([
            'user_id'           => $data['user_id'],
            'news_id'           => $data['news_id'],
            'title'             => $data['title'],
            'content'           => $data['content'],
            'update_user_id'    => $data['update_user_id']
        ]);
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
     * 掲示板投稿通知のデータ保存
     * 引数1：ユーザID, 引数2: ユーザ名, 引数3：グループ名, 引数4: 更新者ユーザID
     */
    public function savePostInfo($user_id, $user_name, $group_name, $update_user_id)
    {
        $title = $group_name.'の掲示板が新規投稿されました';
        $content = $user_name.'さんが'.$group_name.'の掲示板に新たな投稿を追加しました。掲示板にて内容を確認することが出来ます';
        
        $data = [
            'user_id'           => $user_id,
            'news_id'           => $this->getNewsId($user_id),
            'title'             => $title,
            'content'           => $content,
            'update_user_id'    => $update_user_id
        ];
        $data = $this->save($data);

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
     * 全体向けニュース作成時の未読管理データ保存
     * 引数1：保存データ, 引数2：ユーザデータ
     */
    public function savePublicNread($data, $users)
    {
        // 未読管理テーブルに保存
        $nreadRepository = $this->baseGetRepository(NreadManagementRepositoryInterface::class);

        foreach($users as $user) {
            $data['user_id'] = $user->id;
            $nreadRepository->save($data);
        }
        return;
    }

    /**
     * データ削除
     * key: [news_id, user_id]
     */
    public function delete($key)
    {
        DB::delete(
            'delete from news WHERE news_id = ? AND user_id = ?', 
            [$key['news_id'], $key['user_id']]
        );

        // 未読管理テーブルも削除を実行
        $nrkey = $key;
        $nrkey['news_user_id'] = $key['user_id'];

        $nreadRepository = $this->baseGetRepository(NreadManagementRepositoryInterface::class);
        $nreadRepository->delete($nrkey);

        return;
    }

    /**
     * 新規保存用のニュースIDの取得
     * 引数：ユーザID
     */
    public function getNewsId(int $user_id = 0)
    {
        if($this->baseSearchExists(['user_id' => $user_id])) {
            return $this->baseSearchFirst(['user_id' => $user_id], ['news_id' => 'desc'])->news_id + 1;
        }
        return 1;
    }

    /**
     * 全ユーザ情報の取得
     */
    public function getAllUser()
    {
        $userRepository = $this->baseGetRepository(UserRepositoryInterface::class);
        return $userRepository->searchQuery();
    }
}