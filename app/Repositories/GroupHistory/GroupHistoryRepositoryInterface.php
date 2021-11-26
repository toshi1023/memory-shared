<?php

namespace App\Repositories\GroupHistory;

interface GroupHistoryRepositoryInterface
{
    public function searchQuery($conditions=[], $order=[], bool $softDelete=false);
    public function searchGroupId($conditions=[], $order=[], bool $softDelete=false);
    public function searchQueryUsers($conditions=[], $order=[], bool $softDelete=false);
    public function searchUserId($conditions=[], $order=[], bool $softDelete=false);
    public function searchExists($conditions=[], $order=[], bool $softDelete=false);
    public function searchGroupFirst($conditions=[], $order=[], bool $softDelete=false);
    public function searchGroupDetailFirst($conditions=[], $order=[], bool $softDelete=false);
    public function getUsersInfo($conditions, $order=[], int $paginate = 15);
    public function baseSearchFirst($conditions=[], $order=[], bool $softDelete=false);
    public function baseSearchQueryLimit($conditions=[], $order=[], int $limit=10);
    public function baseSearchQueryPaginate($conditions=[], $order=[], int $paginate=10);
    public function baseDelete($id);
    public function baseForceDelete($id);
    public function save($data, $model=null);
    public function saveGroupInfo($user_id, $group_name, $status);
    public function getFamilies($conditions);
    public function confirmGroupHost($user_id, $group_id);
    public function delete($group_id);
}