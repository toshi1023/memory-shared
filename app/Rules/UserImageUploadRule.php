<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Album;

class UserImageUploadRule implements Rule
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
     * 画像投稿者がグループに正式に参加されているかを確認
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return Album::leftjoin('group_histories', 'albums.group_id', '=', 'group_histories.group_id')
                    ->where('albums.id', '=', request()->album_id)
                    ->where('group_histories.user_id', '=', request()->user_id)
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
        return 'このアルバムに画像を投稿するにはグループに加盟する必要があります';
    }
}
