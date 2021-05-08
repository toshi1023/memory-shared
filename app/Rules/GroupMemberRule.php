<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\GroupHistory;

class GroupMemberRule implements Rule
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
     * グループの一員であるかどうかをチェック
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return GroupHistory::where('group_id', '=', request()->group_id)
                           ->where('user_id', '=', $value)
                           ->where('status', '=', config('const.GroupHistory.APPROVAL'))
                           ->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'このグループでアルバムを作成する権限がありません';
    }
}
