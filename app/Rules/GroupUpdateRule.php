<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Group;

class GroupUpdateRule implements Rule
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
     * グループ作成者と一致するかどうか確認
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if(request()->id) {
            return Group::where('id', '=', request()->id)
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
        return 'グループ作成者以外はグループ情報を更新できません';
    }
}
