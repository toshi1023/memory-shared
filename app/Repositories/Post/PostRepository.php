<?php

namespace App\Repositories\Post;

use App\Models\Post;
use App\Repositories\BaseRepository;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\News\NewsRepositoryInterface;
use App\Repositories\Group\GroupRepositoryInterface;
use App\Repositories\GroupHistory\GroupHistoryRepositoryInterface;
use App\Repositories\PostComment\PostCommentRepositoryInterface;

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
                    ->with(['user:id,name,image_file','postComments'])
                    ->get();
    }

    /**
     * 基本クエリ(単体)
     * 引数1: 検索条件, 引数2: ソート条件, 引数3: 削除済みデータの取得フラグ
     */
    public function searchFirst($conditions=[], $order=[], bool $softDelete=false)
    {
        return $this->baseSearchQuery($conditions, $order, $softDelete)
                    ->with(['user:id,name,image_file','postComments'])
                    ->first();
    }

    /**
     * ページネーションを設定
     * 引数1: 検索条件, 引数2: ソート条件, 引数3: 表示件数
     */
    public function searchQueryPaginate($conditions=[], $order=[], int $paginate=10)
    {
        return $this->baseSearchQuery($conditions, $order, false)
                    ->with(['user:id,name,image_file'])
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
     * 投稿通知のデータ保存
     * 引数1：ユーザID, 引数2：グループ名, 引数3：申請ステータス
     */
    public function savePostInfo($user_id, $user_name, $group_name, $update_user_id)
    {
        $newsRepository = $this->baseGetRepository(NewsRepositoryInterface::class);

        return $newsRepository->savePostInfo($user_id, $user_name, $group_name, $update_user_id);
    }

    /**
     * グループ情報の取得
     * 引数：グループID
     */
    public function getGroupInfo($group_id)
    {
        $groupRepository = $this->baseGetRepository(GroupRepositoryInterface::class);

        return $groupRepository->baseSearchFirst(['id' => $group_id]);
    }

    /**
     * グループのメンバー情報の取得
     * 引数：グループID
     */
    public function getGroupMember($group_id)
    {
        $ghRepository = $this->baseGetRepository(GroupHistoryRepositoryInterface::class);

        return $ghRepository->searchQuery(['group_id' => $group_id, 'status' => config('const.GroupHistory.APPROVAL')]);
    }

    /**
     * コメント情報の取得
     * 引数：投稿ID
     */
    public function getPostComment($post_id)
    {
        $pcRepository = $this->baseGetRepository(PostCommentRepositoryInterface::class);

        return $pcRepository->baseSearchQuery(['post_id' => $post_id])->get();
    }

    /**
     * コメント情報の削除
     * 引数：コメントID
     */
    public function deletePostComment($comment_id)
    {
        $pcRepository = $this->baseGetRepository(PostCommentRepositoryInterface::class);

        return $pcRepository->baseDelete($comment_id);
    }
}