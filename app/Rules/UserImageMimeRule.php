<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class UserImageMimeRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * 画像のmimeタイプをチェック
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // 検証結果用
        $bool = false;
        // 許可する画像のmimeタイプ
        $mimeType = [
            'image/jpeg','image/png','image/jpg','image/gif'
        ];

        $bool = in_array($value->getMimeType(), $mimeType);
        
        return $bool;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'アップロード画像 は jpeg, png, jpg, gif, svg タイプのみ有効です';
    }
}
