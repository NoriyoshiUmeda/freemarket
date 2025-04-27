<?php

namespace Database\Factories;

use App\Models\Status;
use Illuminate\Database\Eloquent\Factories\Factory;

class StatusFactory extends Factory
{
    /**
     * このファクトリが対応するモデルクラス
     *
     * @var string
     */
    protected $model = Status::class;

    /**
     * モデルのデフォルトの属性定義
     *
     * @return array
     */
    public function definition()
    {
        return [
            // ステータス名をランダムな単語で生成
            'name' => $this->faker->unique()->word(),
        ];
    }
}
