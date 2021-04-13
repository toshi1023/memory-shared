<?php

namespace App\Repositories\User;

interface UserRepositoryInterface
{
    public function baseSearchQuery($conditions=[], $order=[], bool $softDelete=false);
    public function searchQuery($conditions=[], $order=[], bool $softDelete=false);
    public function baseSearchQueryLimit($conditions=[], $order=[], int $limit);
    public function baseSearchQueryPaginate($conditions=[], $order=[], int $paginate);

}