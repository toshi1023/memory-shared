<?php

namespace App\Repositories\UserImage;

interface UserImageRepositoryInterface
{
    public function searchQueryPaginate($conditions=[], $order=[], int $paginate=30);
    public function baseDelete($id);
    public function baseForceDelete($id);
    public function save($data, $model=null);
}