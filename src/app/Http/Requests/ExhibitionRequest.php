<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
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
            // 商品名: 入力必須
            'name' => 'required|string',

            // 商品説明: 入力必須、最大文字数255
            'description' => 'required|string|max:255',

            // 商品画像: アップロード必須、画像であること、拡張子が.jpegまたは.png
            'image' => 'required|image|mimes:jpeg,png',

            // 商品のカテゴリー: 選択必須、categoriesテーブルに存在するID
            'category_id' => 'required|array',
            'category_id.*' => 'exists:categories,id',

            // 商品の状態: 選択必須、conditionsテーブルに存在するID
            'status_id' => 'required|exists:statuses,id',

            // 商品価格: 入力必須、数値型、0円以上
            'price' => 'required|numeric|min:0',

            'brand' => 'nullable|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '商品名を入力してください',
            'description.required' => '商品説明を入力してください',
            'description.max' => '商品説明は255文字以内で入力してください。',
            'image.required' => '商品画像はアップロード必須です',
            'image.image' => '商品画像はJPEGまたはPNG形式でアップロードしてください',
            'image.mimes' => '商品画像はJPEGまたはPNG形式でアップロードしてください',
            'category_id.required' => '商品のカテゴリーを選択してください',
            'status_id.required' => '商品の状態を選択してください',
            'price.required' => '商品価格を入力してください',
            'price.numeric' => '商品価格は数値で入力してください',
            'price.min' => '商品価格は0円以上で入力してください',
            'brand.max' => 'ブランド名は255文字以内で入力してください',
        ];
    }
}
