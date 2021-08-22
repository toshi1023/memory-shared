<?php

namespace App\Repositories\NreadManagement;

interface NreadManagementRepositoryInterface
{
    public function searchQuery($conditions=[], $order=[], bool $softDelete=false);
    public function baseSearchFirst($conditions=[], $order=[], bool $softDelete=false);
    public function baseSearchQueryLimit($conditions=[], $order=[], int $limit=10);
    public function baseSearchQueryPaginate($conditions=[], $order=[], int $paginate=10);
    public function baseDelete($id);
    public function baseForceDelete($id);
    public function getNewsFirst($conditions=[], $order=[], bool $softDelete=false);
    public function save($data);
    public function delete($key);
}