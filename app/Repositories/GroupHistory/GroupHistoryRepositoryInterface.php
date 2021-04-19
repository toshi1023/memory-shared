<?php

namespace App\Repositories\GroupHistory;

interface GroupHistoryRepositoryInterface
{
    public function baseSearchQuery($conditions=[], $order=[], bool $softDelete=false);
    public function searchQuery($conditions=[], $order=[], bool $softDelete=false);
    public function baseSearchQueryLimit($conditions=[], $order=[], int $limit=10);
    public function baseSearchQueryPaginate($conditions=[], $order=[], int $paginate=10);
    public function save($data, $model=null);
}