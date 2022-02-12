<?php

namespace App\Repositories\Album;

interface AlbumRepositoryInterface
{
    public function baseSearchFirst($conditions=[], $order=[], bool $softDelete=false);
    public function baseSearchQueryPaginate($conditions=[], $order=[], int $paginate=10);
    public function baseDelete($id);
    public function save($data, $model=null);
}