<?php

use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\User;
use App\Models\Category;
use App\Models\Status;

class ItemSeeder extends Seeder
{
    public function run()
    {
        // 出品者のユーザーを取得（デフォルトで1人目のユーザーを設定）
        $user = User::inRandomOrder()->first();
        $userId = $user ? $user->id : 1;

        // `statuses` テーブルのデータを取得（name をキー、id を値とする配列）
        $statusMapping = Status::pluck('id', 'name')->toArray();

        // `categories` テーブルのデータを取得（ランダムに設定するため）
        $categoryIds = Category::pluck('id')->toArray();

        // 商品データ（`status_id` を直接指定）
        $items = [
            ['name' => '腕時計', 'price' => 15000, 'description' => 'スタイリッシュなデザインのメンズ腕時計', 'image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Armani+Mens+Clock.jpg', 'status_id' => $statusMapping['良好'] ?? null],
            ['name' => 'HDD', 'price' => 5000, 'description' => '高速で信頼性の高いハードディスク', 'image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/HDD+Hard+Disk.jpg', 'status_id' => $statusMapping['目立った傷や汚れなし'] ?? null],
            ['name' => '玉ねぎ3束', 'price' => 300, 'description' => '新鮮な玉ねぎ3束のセット', 'image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/iLoveIMG+d.jpg', 'status_id' => $statusMapping['やや傷や汚れあり'] ?? null],
            ['name' => '革靴', 'price' => 4000, 'description' => 'クラシックなデザインの革靴', 'image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Leather+Shoes+Product+Photo.jpg', 'status_id' => $statusMapping['状態が悪い'] ?? null],
        ];

        // `user_id`, `category_id` を設定し、一括挿入用の配列を準備
        foreach ($items as &$item) {
            $item['user_id'] = $userId;
            $item['category_id'] = $categoryIds ? $categoryIds[array_rand($categoryIds)] : null; // カテゴリーをランダムに選択
            $item['created_at'] = now();
            $item['updated_at'] = now();
        }

        // 一括登録（パフォーマンス向上）
        Item::insert($items);
    }
}
