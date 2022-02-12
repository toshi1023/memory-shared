<?php

namespace App\Repositories\PostComment;

interface PostCommentRepositoryInterface
{
    public function searchQuery($conditions=[], $order=[], bool $softDelete=false);
    public function searchFirst($conditions=[], $order=[], bool $softDelete=false);
    public function save($data, $model=null);
    public function baseDelete($id);
    public function baseConfirmGroupMember($user_id, $group_id);
}