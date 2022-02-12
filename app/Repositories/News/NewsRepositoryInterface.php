<?php

namespace App\Repositories\News;

interface NewsRepositoryInterface
{
    public function searchQueryPaginate($conditions=[], $order=[], int $paginate=15);
    public function baseSearchFirst($conditions=[], $order=[], bool $softDelete=false);
    public function save($data);
    public function saveWelcomeInfo($user_id);
    public function saveGroupInfo($user_id, $group_name, $status);
    public function savePostInfo($user_id, $user_name, $group_name, $update_user_id);
    public function savePublicNread($data, $users);
    public function delete($key);
    public function baseAdminCertification($onetime_password);
    public function getNewsId(int $user_id = 0);
    public function getAllUser();
}