<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

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
                'max:255',
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
            'profile_image.image' => '画像ファイルを選択してください。',
            'profile_image.mimes' => 'プロフィール画像はJPEGまたはPNG形式でアップロードしてください。',

            'name.required' => 'ユーザー名を入力してください。',
            'name.max' => 'ユーザー名は20文字以内で入力してください。',

            'postal_code.required' => '郵便番号を入力してください。',
            'postal_code.regex' => '郵便番号はハイフンありの8文字（例：123-4567）で入力してください。',

            'address.required' => '住所を入力してください。',
        ];
    }
}
