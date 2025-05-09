<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
{
        public function authorize()
    {
        return true;
    }

        public function rules()
    {
        return [

            'name' => 'required|string',


            'description' => 'required|string|max:255',


            'image' => 'required|image|mimes:jpeg,png',


            'category_id' => 'required|array',
            'category_id.*' => 'exists:categories,id',


            'status_id' => 'required|exists:statuses,id',


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
