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
            'image_file'            => 'image|mimes:jpeg,png,jpg,gif|max:10240',
            'host_user_id'          => ['required', new GroupMemberRule, new AlbumUpdateRule]
        ];
    }

    /**
     * メッセージをカスタマイズ
     */
    public function messages()
    {
        return [
            "name.required"         => "アルバム名は必須です",
            "name.max"              => "アルバム名は50文字以内で入力してください",
            "mines"                 => "指定された拡張子（PNG/JPG/GIF）ではありません。",
            "image_file.max"        => "10Mを超えています。",
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
