<?php

namespace App\Repositories\MessageHistory;

interface MessageHistoryRepositoryInterface
{
    public function baseSearchFirst($conditions=[], $order=[], bool $softDelete=false);
    public function getMessages($conditions=[], bool $softDelete=false, $paginate=20);
    public function getMessage($conditions=[], bool $softDelete=false);
    public function getMessageList($user_id, int $paginate=15);
    public function getUser($conditions=[], bool $softDelete=false);
    public function save($data, $model=null);
    public function saveMread($data);
    public function delete($id, $user_id);
}