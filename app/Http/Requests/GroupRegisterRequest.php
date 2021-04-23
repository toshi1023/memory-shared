<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class GroupRegisterRequest extends FormRequest
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
            // グループのバリデーションチェック
            'name'                  => ['required', 'max:50'],
            'image_file'            => 'image|mimes:jpeg,png,jpg,gif|max:1024',
        ];
    }

    /**
     * メッセージをカスタマイズ
     */
    public function messages()
    {
        return [
            "name.max"                      => "グループ名は20文字以内で入力してください",
            "mines"                         => "指定された拡張子（PNG/JPG/GIF）ではありません。",
            "image_file.max"                => "1Mを超えています。",
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
        ],400);
        throw new HttpResponseException($res);
    }
}
