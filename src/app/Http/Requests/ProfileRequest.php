<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // 認可のチェック（必要に応じて変更）
    }

    public function rules(): array
    {
        return [
            'profile_image' => 'nullable|image|mimes:jpeg,png|',
        ];
    }

    public function messages(): array
    {
        return [
            'profile_image.image' => 'プロフィール画像はJPEGまたはPNG形式でアップロードしてください',
            'profile_image.mimes' => 'プロフィール画像はJPEGまたはPNG形式でアップロードしてください',
        ];
    }
}
