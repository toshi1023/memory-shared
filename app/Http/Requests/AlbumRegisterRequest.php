<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Rules\GroupMemberRule;
use App\Rules\AlbumUpdateRule;

class AlbumRegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // アルバムのバリデーションチェック
            'name'                  => ['required', 'max:50'],
            'host_user_id'          => ['required', new GroupMemberRule, new AlbumUpdateRule]
        ];
    }

    /**
     * メッセージをカスタマイズ
     */
    public function messages()
    {
        return [
            "name.max"              => "アルバム名は50文字以内で入力してください",
        ];
    }

    /**
     * エラー内容をJson形式でリターン
     */
    protected function failedValidation(Validator $validator)
    {
        $res = response()->json([
            'status' => 400,
            'errors' => $validator->errors(),
        ], 400, [], JSON_UNESCAPED_UNICODE);
        throw new HttpResponseException($res);
    }
}
