<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Repositories\BaseRepository;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function getModel()
    {
        return $this->baseGetModel(User::class);
    }

    public function baseQuery()
    {
        $query = $this->model::query();

        return $query->select()->first();
    }
}