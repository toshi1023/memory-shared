<?php

namespace App\Repositories\MessageHistory;

interface MessageHistoryRepositoryInterface
{
    public function searchQuery($conditions=[], $order=[], bool $softDelete=false);
    public function baseSearchFirst($conditions=[], $order=[], bool $softDelete=false);
    public function baseSearchQueryLimit($conditions=[], $order=[], int $limit=10);
    public function baseSearchQueryPaginate($conditions=[], $order=[], int $paginate=10);
    public function baseDelete($id);
    public function baseForceDelete($id);
    public function getMessages($conditions=[], bool $softDelete=false, $paginate=20);
    public function getMessage($conditions=[], bool $softDelete=false);
    public function getMessageList($user_id, int $paginate=15);
    public function getUser($conditions=[], bool $softDelete=false);
    public function save($data, $model=null);
    public function saveMread($data);
    public function delete($id, $user_id);
}