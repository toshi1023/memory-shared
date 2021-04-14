<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;

abstract class  BaseRepository
{
    protected $model;

    /**
     * 特定のモデルをインスタンス化
     * 引数:モデルクラス (例) User::class
     */
    protected function baseGetModel($model): Model
    {
        // 指定したテーブルをインスタンス化して返す
        return $this->model = app()->make($model);
    }

    /**
     * 検索クエリの基盤
     * 引数1: 検索条件, 引数2: ソート条件, 引数3: 削除済みデータの取得フラグ
     *   ※引数1の条件指定方法
     *     例）['id' => 21]  もしくは  ['name@like' => 'test1']
     *   ※引数2の条件指定方法
     *     例）['id' => 'desc']  もしくは  ['@custom' => 'updated_at desc']
     */
    public function baseSearchQuery($conditions=[], $order=[], bool $softDelete=false)
    {
        $query = $this->model::query();

        // 削除済みデータの取得
        if($softDelete) $query->withTrashed();

        // メインテーブルSELECT
        $query->select($this->model->getTable().".*");

        // 検索条件
        if($conditions) $query = $this->baseGetConditions($query, $this->model->getTable(), $conditions);

        // ソート条件
        if($order) {
            foreach($order as $key => $value) {
                // カスタムオーダーの場合
                if (preg_match('/@custom/', $key)) {
                    // 文字列をorder by節の値として指定するために使用
                    $query->orderByRaw($value);
                } else {
                    $query->orderBy($key, $value);
                }
            }
        }
        // クエリ結果をリターン
        return $query;
    }

    /**
     * 検索条件作成
     * 引数1: クエリ, 引数2: テーブル名, 引数3: 検索条件
     * @return mixed
     */
    protected function baseGetConditions($query, $table, $conditions=[]) {
        $table = $table.".";

        foreach($conditions as $key => $value) {
            if (preg_match('/@like/', $key)) {
                // LIKE検索
                $query->where(str_replace("@like", "", $key), 'like', '%'.$value.'%');
            } else if (preg_match('/@not/', $key)) {
                // NOT検索
                $query->where(str_replace("@not", "", $key), '!=', $value);
            } else if (preg_match('/@>=/', $key)) {
                // 大なりイコール
                $query->where(str_replace("@>=", "", $key), '>=', $value);
            } else if (preg_match('/@<=/', $key)) {
                // 小なりイコール
                $query->where(str_replace("@<=", "", $key), '<=', $value);
            } else if (preg_match('/@</', $key)) {
                // 大なり
                $query->where(str_replace("@<", "", $key), '<', $value);
            } else if (preg_match('/@>/', $key)) {
                // 小なり
                $query->where(str_replace("@>", "", $key), '>', $value);
            } else if (preg_match('/@in/', $key)) {
                // IN
                $query->whereIn(str_replace("@in", "", $key), $value);
            } else if (preg_match('/@not_in/', $key)) {
                // NotIN
                $query->whereNotIn(str_replace("@not_in", "", $key), $value);
            } else if (preg_match('/@and_or/', $key)) {
                // And-OR
                // ※この場合のみ「value」部分は「value1==value2」と指定すること
                $values = explode('==', $value);
                $query->where(function($query) use($key, $values) {
                    foreach($values as $val) {
                        // ※判定値がnullの場合はWhereNullで判定
                        if ($val == "null") {
                            $query->orWhereNull(str_replace("@and_or", "", $key));
                        } else {
                            $query->orWhere(str_replace("@and_or", "", $key), $val);
                        }
                    }
                });
            } else if (preg_match('/@is_null/', $key)) {
                // Is Null
                $query->whereNull(str_replace("@is_null", "", $key));
            } else if (preg_match('/@is_not_null/', $key)) {
                // Is Not Null
                $query->whereNotNull(str_replace("@is_not_null", "", $key));
            } else if (preg_match('/@custom/', $key)) {
                // カスタム条件
                $query->whereRaw($value);
            } else {
                // 通常検索
                $query->where($key, $value);
            }
        }
        return $query;
    }

    /**
     * 表示件数を限定
     * 引数1: 検索条件, 引数2: ソート条件, 引数3: 表示件数
     */
    public function baseSearchQueryLimit($conditions=[], $order=[], int $limit)
    {
        return $this->baseSearchQuery($conditions, $order, false)->limit($limit);
    }

    /**
     * ページネーションを設定
     * 引数1: 検索条件, 引数2: ソート条件, 引数3: 表示件数
     */
    public function baseSearchQueryPaginate($conditions=[], $order=[], int $paginate)
    {
        return $this->baseSearchQuery($conditions, $order, false)->paginate($paginate);
    }

    /**
     * 指定データ保存
     * 引数1: 保存データ, 引数2: モデルクラス (例) User::class, 引数3: トランザクションフラグ
     * @param $data
     * @param Model $model
     * @param bool $transaction
     * @return Model|mixed
     * @throws Exception
     */
    protected function baseSave($data, $model=null, $transaction=true) {
        if ($transaction) DB::beginTransaction();

        try {
            // Modelの指定がある場合
            if($model) {
                $model = $this->baseGetModel($model);
            } else {
                $model = $this->model;
            }

            // UPDATE
            if (key_exists('id', $data) && $data['id']) {
                $model = $model::query()->where('id', $data["id"])->first();
            }
            // 更新者ID
            $model->update_user_id  = $this->baseGetUserId();
            
            // 保存処理
            $model->fill($data);
            $model->save();

            if ($transaction) DB::commit();
            return $model;
        } catch (Exception $e) {
            if ($transaction) DB::rollBack();
            
            Log::error(config('const.SystemMessage.SYSTEM_ERR').__FUNCTION__.":".$e->getMessage());
            throw new Exception($e);
        }
    }

     /**
     * ログイン中ユーザーID取得
     * @return \Illuminate\Contracts\Auth\Authenticatable|int|null
     */
    private function baseGetUserId() {
        return Auth::user()->id;
    }
}