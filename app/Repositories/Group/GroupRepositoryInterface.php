<?php

namespace App\Repositories\Group;

interface GroupRepositoryInterface
{
    public function searchFirst($conditions=[], $order=[], bool $softDelete=false);
    public function searchQueryPaginate($conditions=[], $order=[], int $paginate=20);
    public function baseSearchFirst($conditions=[], $order=[], bool $softDelete=false);
    public function getParticipants($conditions, $order=[], bool $softDelete=false);
    public function getUsersInfo($conditions, $order=[], int $paginate = 15);
    public function save($data, $model=null);
    public function delete($id);
}