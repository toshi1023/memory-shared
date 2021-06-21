<?php

namespace App\Repositories\Album;

interface AlbumRepositoryInterface
{
    public function searchQuery($conditions=[], $order=[], bool $softDelete=false);
    public function baseSearchFirst($conditions=[], $order=[], bool $softDelete=false);
    public function baseSearchQueryLimit($conditions=[], $order=[], int $limit=10);
    public function baseSearchQueryPaginate($conditions=[], $order=[], int $paginate=10);
    public function baseDelete($id);
    public function baseForceDelete($id);
    public function getGroup($conditions);
    public function getImages($conditions, int $paginate=30);
    public function getVideos($conditions, int $paginate=15);
    public function save($data, $model=null);
}