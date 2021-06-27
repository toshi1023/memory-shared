<?php

namespace App\Repositories\Family;

use App\Models\Family;
use App\Repositories\BaseRepository;
use Exception;
use Illuminate\Support\Facades\Auth;

class FamilyRepository extends BaseRepository implements FamilyRepositoryInterface
{
    protected $model;

    public function __construct(Family $model)
    {
        $this->model = $model;
    }

    /**
     * 基本クエリ
     * 引数1: 検索条件, 引数2: ソート条件, 引数3: 削除済みデータの取得フラグ
     */
    public function searchQuery($conditions=[], $order=[], bool $softDelete=false)
    {
        return $this->baseSearchQuery($conditions, $order, $softDelete)->get();
    }

    /**
     * familiesテーブルにデータが保存されているか確認
     * 引数1: ユーザID, 引数2: ユーザID
     */
    public function confirmFamily($user_id1, $user_id2)
    {
        $exists = false;

        $exists = $this->baseSearchQuery(['user_id1' => $user_id1, 'user_id2' => $user_id2])->exists();

        if(!$exists) {
            $exists = $this->baseSearchQuery(['user_id2' => $user_id1, 'user_id1' => $user_id2])->exists();
        }

        return $exists;
    }

    /**
     * データ保存
     */
    public function save($data)
    {
        return Family::create([
            'user_id1' => $data['user_id1'],
            'user_id2' => $data['user_id2']
        ]);
    }

    /**
     * データ削除
     */
    public function delete($user_id1, $user_id2)
    {
        $model = $this->baseSearchFirst(['user_id1' => $user_id1, 'user_id2' => $user_id2]);

        // データが見つからない場合
        if(!$model) {
            $model = $this->baseSearchFirst(['user_id1' => $user_id2, 'user_id2' => $user_id1]);

            // データが一切見つからない場合エラーを返す
            if(!$model) {
                throw new Exception('削除対象のデータが存在しません');
                return;
            }
        }
        // データを完全削除
        return $model->forceDelete();
    }
}