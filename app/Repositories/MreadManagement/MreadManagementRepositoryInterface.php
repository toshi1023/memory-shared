<?php

namespace App\Repositories\MreadManagement;

interface MreadManagementRepositoryInterface
{
    public function searchQuery($conditions=[], $order=[], bool $softDelete=false);
    public function save($data);
    public function delete($key, $messages);
}