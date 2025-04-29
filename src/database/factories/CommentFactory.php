<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    // 対応モデル
    protected $model = Comment::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),         // 認証ユーザー
            'item_id' => Item::factory(),         // 対象商品
            'comment' => $this->faker->sentence,  // コメント本文
            // created_at, updated_at は自動で挿入されます
        ];
    }
}
