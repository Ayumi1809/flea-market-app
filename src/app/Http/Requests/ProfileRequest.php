<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
            'profile_image' => [
                'nullable',
                'image',
                'mimes:jpeg,png',
            ],

            'name' => [
                'required',
                'string',
                'max:20',
            ],

            'postal_code' => [
                'required',
                'regex:/^\d{3}-\d{4}$/',
            ],

            'address' => [
                'required',
                'string',
            ],

            'building' => [
                'nullable',
                'string',
                'max:255',
            ],
        ];
    }

    public function messages()
    {
        return [
            // プロフィール画像
            'profile_image.image' => '画像ファイルを選択してください。',
            'profile_image.mimes' => 'プロフィール画像はJPEGまたはPNG形式でアップロードしてください。',

            // ユーザー名
            'name.required' => 'ユーザー名を入力してください。',
            'name.max' => 'ユーザー名は20文字以内で入力してください。',

            // 郵便番号
            'postal_code.required' => '郵便番号を入力してください。',
            'postal_code.regex' => '郵便番号はハイフンありの8文字（例：123-4567）で入力してください。',

            // 住所
            'address.required' => '住所を入力してください。',
        ];
    }
}
