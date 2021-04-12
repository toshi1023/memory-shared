<?php

namespace App\Repositories\User;

interface UserRepositoryInterface
{
    public function baseSerchQuery($conditions=[], $order=[], bool $softDelete=false);
    public function baseQuery();
}