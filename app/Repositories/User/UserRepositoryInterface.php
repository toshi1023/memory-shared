<?php

namespace App\Repositories\User;

interface UserRepositoryInterface
{
    public function baseSearchQuery($conditions=[], $order=[], bool $softDelete=false);
    public function baseQuery();
}