<?php

namespace App\Repositories\User;

interface UserRepositoryInterface
{
    public function searchQuery($conditions=[], $order=[], bool $softDelete=false);
    public function searchFirst($conditions=[], $order=[], bool $softDelete=false);
    public function searchQueryLimit($conditions=[], $order=[], int $limit=10);
    public function searchQueryPaginate($conditions=[], $order=[], int $paginate=10);
    public function baseDelete($id);
    public function save($data, $model=null);
    public function getGroups($conditions);
    public function getFamilies($conditions, $order=[], bool $softDelete=false);
    public function getParticipating($conditions, $order=[], bool $softDelete=false);
}