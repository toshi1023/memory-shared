<?php

namespace App\Http\Requests;

use Illuminate\contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UserRegisterRequest extends FormRequest
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
        if($this->register_mode == 'edit' && $this->password == null && request()->password_confirmation == null) {
            return [
                // ユーザのバリデーションチェック
                'name'                  => ['required', 'max:15', Rule::unique('users')->ignore($this->id, 'id')],
                'email'                 => ['required', 'email', 'max:50', 'regex:/^[a-zA-Z0-9\.\-@]+$/'],
                'image_file'            => 'image|mimes:jpeg,png,jpg,gif|max:1024',
            ];
        }
        
        return [
            // ユーザのバリデーションチェック
            'name'                  => ['required', 'max:15', Rule::unique('users')->ignore($this->id, 'id')],
            'email'                 => ['required', 'email', 'max:50', 'regex:/^[a-zA-Z0-9\.\-@]+$/'],
            'password'              => ['required', 'min:6', 'confirmed', 'regex:/^[0-9a-zA-Z\_@!?#%&]+$/'],
            'password_confirmation' => ['required', 'min:6', 'regex:/^[0-9a-zA-Z\_@!?#%&]+$/'],
            'image_file'            => 'image|mimes:jpeg,png,jpg,gif|max:1024',
        ];
    }

    /**
     * メッセージをカスタマイズ
     */
    public function messages()
    {
        return [
            "unique"                        => 'このユーザ名はすでに使用されています',
            "mines"                         => "指定された拡張子（PNG/JPG/GIF）ではありません。",
            "name.max"                      => "ユーザ名は15文字以内で入力してください",
            "image_file.max"                => "1Mを超えています。",
            'email.regex'                   => '@以前は半角英数字で入力してください',
            "email.max"                     => "メールアドレスは50文字以内で入力してください",
            'password.regex'                => 'パスワードは半角英数字及び「_@!?#%&」の記号のみで入力してください',
            'password_confirmation.regex'   => 'パスワード（確認）は半角英数字及び「_@!?#%&」の記号のみで入力してください',
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
