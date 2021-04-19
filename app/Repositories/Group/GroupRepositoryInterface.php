<?php

namespace App\Repositories\Group;

interface GroupRepositoryInterface
{
    public function baseSearchQuery($conditions=[], $order=[], bool $softDelete=false);
    public function searchQuery($conditions=[], $order=[], bool $softDelete=false);
    public function baseSearchQueryLimit($conditions=[], $order=[], int $limit=10);
    public function baseSearchQueryPaginate($conditions=[], $order=[], int $paginate=10);
    public function save($data, $model=null);
}