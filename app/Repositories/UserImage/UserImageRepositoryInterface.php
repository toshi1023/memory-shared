<?php

namespace App\Repositories\UserImage;

interface UserImageRepositoryInterface
{
    public function searchQuery($conditions=[], $order=[], bool $softDelete=false);
    public function searchQueryPaginate($conditions=[], $order=[], int $paginate=30);
    public function baseSearchFirst($conditions=[], $order=[], bool $softDelete=false);
    public function baseSearchQueryLimit($conditions=[], $order=[], int $limit=10);
    public function baseSearchQueryPaginate($conditions=[], $order=[], int $paginate=10);
    public function baseDelete($id);
    public function baseForceDelete($id);
    public function save($data, $model=null);
}