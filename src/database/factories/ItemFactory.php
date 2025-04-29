<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Item;
use App\Models\Status;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    // この Factory が操作するモデル
    protected $model = Item::class;

    public function definition()
    {
        return [
            // 関連モデルもファクトリで作成
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
            'status_id' => Status::factory(),
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->sentence(10),
            'price' => $this->faker->numberBetween(100, 10000),
            'image' => 'sample.jpg',
            'brand' => $this->faker->company(),
        ];
    }
}
