<?php

namespace App\Repositories\UserVideo;

interface UserVideoRepositoryInterface
{
    public function searchQueryPaginate($conditions=[], $order=[], int $paginate=10);
    public function baseDelete($id);
    public function baseForceDelete($id);
    public function save($data, $model=null);
}