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
        $conditions = [];

        foreach($request->all() as $key => $value) {
            // ソート条件は排除(頭文字に"sort_"がつくキー)
            if(!preg_match('/sort_/', substr($key, 0, 5))) {
                $conditions[(string)$key] = $value;
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
        $order = [];

        foreach($request->all() as $key => $value) {
            // ソート条件のみを設定(頭文字に"sort_"がつくキー)
            if(preg_match('/sort_/', substr($key, 0, 5))) {
                // "sort_"以降の文字列をキーに設定
                $order[(string)substr($key, 5, strlen($key))] = $value;
            }
        }
        return $order;
    }

    /**
     * ワンタイムパスワード発行
     * 引数: アプリ表示用のカスタムフラグ
     *   ※12文字で設定(大文字英数字で表示)
     *   ※1とI、0とOは設定から省く
     * @param $id
     * @return string
     */
    public function issueOnetimePassword(bool $custom=true) {

        // パスワード発行に利用する文字列と数字の配列を用意
        $str_list = range('A', 'Z');
        $str_list = array_diff($str_list, array('I', 'O')); // パスワードの除外文字を設定

        $number_list = range(1, 9);
        $number_list = array_diff($number_list, array(1)); // パスワードの除外文字を設定

        // パスワード発行用の文字と数字を結合
        $password_list = array_merge($str_list, $number_list);

        // パスワードの発行
        $password = '';
        for($i=0; $i<12; $i++) {
            $password .= $password_list[array_rand($password_list)];
        }

        // アプリ表示用にカスタマイズ
        if($custom) {
            $confirmPassword = str_split($password, 4);
            $password = $confirmPassword[0].'-'.$confirmPassword[1].'-'.$confirmPassword[2];
        } 
        
        return $password;
    }
}