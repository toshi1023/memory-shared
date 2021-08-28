<?php

namespace App\Repositories\Post;

use App\Models\Post;
use App\Repositories\BaseRepository;
use App\Repositories\NreadManagement\NreadManagementRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;

class PostRepository extends BaseRepository implements PostRepositoryInterface
{
    protected $model;

    public function __construct(Post $model)
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
                    ->with(['postComments'])
                    ->get();
    }

    /**
     * 基本クエリ(単体)
     * 引数1: 検索条件, 引数2: ソート条件, 引数3: 削除済みデータの取得フラグ
     */
    public function searchFirst($conditions=[], $order=[], bool $softDelete=false)
    {
        return $this->baseSearchQuery($conditions, $order, $softDelete)
                    ->with(['postComments'])
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
     * 掲示板投稿通知のデータ保存
     * 引数1：ユーザID, 引数2：グループ名
     */
    public function savePostInfo($user_id, $group_name, $status)
    {
        $title = $group_name.'の掲示板が新規投稿されました';
        $content = $group_name.'の掲示板に新たな投稿が追加されました。掲示板にて内容を確認することが出来ます';
        
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

    /**
     * 全ユーザ情報の取得
     */
    public function getAllUser()
    {
        $userRepository = $this->baseGetRepository(UserRepositoryInterface::class);
        return $userRepository->searchQuery();
    }
}