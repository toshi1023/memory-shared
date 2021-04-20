<?php

namespace App\Repositories\User;

interface UserRepositoryInterface
{
    public function baseSearchQuery($conditions=[], $order=[], bool $softDelete=false);
    public function searchQuery($conditions=[], $order=[], bool $softDelete=false);
    public function searchFirst($conditions=[], $order=[], bool $softDelete=false);
    public function baseSearchQueryLimit($conditions=[], $order=[], int $limit=10);
    public function baseSearchQueryPaginate($conditions=[], $order=[], int $paginate=10);
    public function save($data, $model=null);
    public function getGroups($conditions);
    public function getFriends($conditions, $order=[], bool $softDelete=false);
}