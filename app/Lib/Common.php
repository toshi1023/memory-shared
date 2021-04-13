<?php
namespace App\Lib;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * 共通処理クラス
 * Class Common
 */
class Common {

    /**
     * 検索条件の配列生成
     */
    public static function setConditions(Request $request)
    {
        foreach($request->all() as $key => $value) {
            // ソート条件は排除(頭文字に"sort_"がつくキー)
            if(!preg_match('/sort_/', substr($key, 0, 5))) {
                $conditions = [
                    (string)$key => $value,
                ];
            }
        }
        return $conditions;
    }

    /**
     * ソート条件の配列生成
     *   キーは頭文字に"sort_"が必要
     *   例）idをソートするときは、sort_id
     */
    public static function setOrder(Request $request)
    {
        foreach($request->all() as $key => $value) {
            // ソート条件のみを設定(頭文字に"sort_"がつくキー)
            if(preg_match('/sort_/', substr($key, 0, 5))) {
                $order = [
                    // "sort_"以降の文字列をキーに設定
                    (string)substr($key, 5, strlen($value)) => $value,
                ];
            }
        }
        return $order;
    }
}