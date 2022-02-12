<?php

namespace App\Repositories\NreadManagement;

interface NreadManagementRepositoryInterface
{
    public function searchQueryCount($conditions=[], $order=[], bool $softDelete=false);
    public function getNewsFirst($conditions=[], $order=[], bool $softDelete=false);
    public function save($data);
    public function delete($key);
}