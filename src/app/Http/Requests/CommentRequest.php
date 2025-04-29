<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
{
    // 認証済みでなくてもバリデーションを通す場合は true にするか、認証チェックを自前で実装
    public function authorize()
    {
        // 必要なら true を返すか、認証チェックを入れる
        return true;
    }

    public function rules()
    {
        return [
            'comment' => 'required|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'comment.required' => 'コメントを入力してください',
            'comment.max' => 'コメントは255文字以内で入力してください',
        ];
    }
}
