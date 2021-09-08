<?php

namespace App\Repositories\User;

interface UserRepositoryInterface
{
    public function searchQuery($conditions=[], $order=[], bool $softDelete=false);
    public function searchFirst($conditions=[], $order=[], bool $softDelete=false);
    public function searchQueryLimit($conditions=[], $order=[], int $limit=10);
    public function searchQueryPaginate($conditions=[], $order=[], int $paginate=10);
    public function baseDelete($id);
    public function baseForceDelete($id);
    public function save($data, $model=null);
    public function saveOnePass($onetime_password, $user_id);
    public function getGroups($conditions);
    public function getFamilies($conditions, $order=[]);
    public function getParticipating($conditions, $order=[], bool $softDelete=false);
    public function getMessageList($user_id);
}