<?php

namespace App\Repositories\Family;

interface FamilyRepositoryInterface
{
    public function searchQuery($conditions=[], $order=[], bool $softDelete=false);
    public function baseSearchFirst($conditions=[], $order=[], bool $softDelete=false);
    public function baseSearchQueryLimit($conditions=[], $order=[], int $limit=10);
    public function baseSearchQueryPaginate($conditions=[], $order=[], int $paginate=10);
    public function delete($user_id1, $user_id2);
    public function save($data);
    public function confirmFamily($user_id1, $user_id2);
}