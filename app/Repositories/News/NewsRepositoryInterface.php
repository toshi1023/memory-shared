<?php

namespace App\Repositories\News;

interface NewsRepositoryInterface
{
    public function searchQuery($conditions=[], $order=[], bool $softDelete=false);
    public function searchFirst($conditions=[], $order=[], bool $softDelete=false);
    public function baseSearchFirst($conditions=[], $order=[], bool $softDelete=false);
    public function baseSearchQueryLimit($conditions=[], $order=[], int $limit=10);
    public function baseSearchQueryPaginate($conditions=[], $order=[], int $paginate=10);
    public function save($data, $model=null);
    public function saveGroupInfo($user_id, $group_name, $status);
    public function delete($user_id, $news_id);
    public function baseAdminCertification($onetime_password);
    public function getNewsId(int $user_id = 0);
}