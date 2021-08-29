<?php

namespace App\Repositories\Post;

interface PostRepositoryInterface
{
    public function searchQuery($conditions=[], $order=[], bool $softDelete=false);
    public function searchFirst($conditions=[], $order=[], bool $softDelete=false);
    public function baseSearchFirst($conditions=[], $order=[], bool $softDelete=false);
    public function baseSearchQueryLimit($conditions=[], $order=[], int $limit=10);
    public function baseSearchQueryPaginate($conditions=[], $order=[], int $paginate=10);
    public function save($data, $model=null);
    public function savePostInfo($user_id, $user_name, $group_name, $update_user_id);
    public function getGroupInfo($group_id);
    public function baseDelete($id);
    public function getGroupMember($group_id);
    public function confirmGroupMember($user_id, $group_id);
    public function getPostComment($post_id);
    public function deletePostComment($comment_id);
}