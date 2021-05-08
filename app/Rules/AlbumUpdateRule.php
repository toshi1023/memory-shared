<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Album;

class AlbumUpdateRule implements Rule
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
     * アルバム作成者と一致するかどうか確認
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if(request()->id) {
            return Album::where('id', '=', request()->id)
                        ->where('host_user_id', '=', $value)
                        ->exists();
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'アルバム作成者以外はアルバム情報を更新できません';
    }
}
