<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Group;

class GroupNameRule implements Rule
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
     * グループを公開する場合はグループ名の重複を禁止する
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if(request()->private_flg === config('const.Group.PUBLIC')) {
            $flg = Group::where('private_flg', '=', config('const.Group.PUBLIC'))
                        ->where('name', '=', $value)
                        ->exists();
            if($flg) return false;
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
        return '公開する場合には重複したグループ名を使用できません。非公開にするか、グループ名を変更してください';
    }
}
