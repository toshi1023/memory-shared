<?php
namespace App\Lib;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Exception;

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
     * in条件用の配列生成
     * 引数: 検索条件
     */
    public static function setInCondition(array $conditions)
    {
        $values = [];
        foreach($conditions as $index => $property) {
            foreach($property as $key => $value) {
                // group_idの値をセット
                $values[] = $value;
            }
        }

        return $values;
    }

    /**
     * json形式保存用の配列生成
     * 引数: 値
     *  ※black_list[{ユーザID}] でリストを取得
     */
    public static function setJsonType(array $data)
    {
        $values = [];
        foreach($data as $value) {
            $values[$value] = (int)$value;
        }

        return $values;
    }

    /**
     * ワンタイムパスワード発行
     * 引数: アプリ表示用のカスタムフラグ
     *   ※12文字で設定(大文字英字と小文字数字で表示)
     *   ※1とI、0とOは設定から省く
     * @param $id
     * @return string
     */
    public static function issueOnetimePassword(bool $custom=true) {

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

        // アプリ表示用にカスタマイズ(表示例: XXXX-XXXX-XXXX)
        if($custom) {
            $confirmPassword = str_split($password, 4);
            $password = $confirmPassword[0].'-'.$confirmPassword[1].'-'.$confirmPassword[2];
        } 
        
        return $password;
    }

    /**
     * ファイル名の設定
     * 引数：ファイル情報
     */
    public static function getFilename($file)
    {
        $tmp_name   = md5(microtime());                    // ファイル名取得(microtime() : Unixタイムスタンプ)
        $ext        = $file->getClientOriginalExtension(); // 拡張子GET
        $image_name = $tmp_name.".".$ext;

        return $image_name;
    }

    /**
     * ユニークなファイル名の設定
     * 引数1：ファイル情報, 引数2: id (※ユニークであることが必須)
     */
    public static function getUniqueFilename($file, $id)
    {
        $tmp_name   = md5(microtime()).$id;                // ファイル名取得(microtime() : Unixタイムスタンプ)
        $ext        = $file->getClientOriginalExtension(); // 拡張子GET
        $image_name = $tmp_name.".".$ext;

        return $image_name;
    }

     /**
     * ファイル名から拡張子取得(.付き)
     * @param $name
     * @return bool|string
     */
    public static function getExt($name) {
        return substr($name, strrpos($name, '.'));
    }

    /**
     * ファイルアップロード用メソッド(アップロード先: S3)
     * 引数1: ファイル, 引数2: カテゴリー, 引数3: フォルダ名に使用するための値, 引数4: ファイル名
     */
    public static function fileSave($file, $category, $foldername, $filename)
    {
        if ($file){
            try {
                // バケットの`{バケット名}/{カテゴリー名}/{ニックネーム名}`フォルダへアップロード
                Storage::disk('s3')->putFileAs($category.'/'.$foldername, $file, $filename, 'public');

                return true;

            } catch (Exception $e) {
                Log::error(config('const.SystemMessage.SYSTEM_ERR').'App\Lib\Common::'.__FUNCTION__.":".$e->getMessage());
                return false;
            }
        }
    }
}