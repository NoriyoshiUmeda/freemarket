<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    /** どのモデルに対応するか */
    protected $model = Category::class;

    /** デフォルトの状態定義 */
    public function definition()
    {
        return [
            // カテゴリ名は適当に Faker で生成
            'category' => $this->faker->unique()->word(),
        ];
    }
}
