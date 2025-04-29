<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Item;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    public function run()
    {
        // 現在日時
        $now = Carbon::now();

        // 商品サンプルデータ
        $products = [
            [
                'name' => '腕時計',
                'price' => 15000,
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'image' => 'images/Armani+Mens+Clock.jpg',
                'condition' => '良好',
                'category' => 'ファッション',
            ],
            [
                'name' => 'HDD',
                'price' => 5000,
                'description' => '高速で信頼性の高いハードディスク',
                'image' => 'images/HDD+Hard+Disk.jpg',
                'condition' => '目立った傷や汚れなし',
                'category' => '家電',
            ],
            [
                'name' => '玉ねぎ3束',
                'price' => 300,
                'description' => '新鮮な玉ねぎ3束のセット',
                'image' => 'images/iLoveIMG+d.jpg',
                'condition' => 'やや傷や汚れあり',
                'category' => 'キッチン',
            ],
            [
                'name' => '革靴',
                'price' => 4000,
                'description' => 'クラシックなデザインの革靴',
                'image' => 'images/Leather+Shoes+Product+Photo.jpg',
                'condition' => '状態が悪い',
                'category' => 'ファッション',
            ],
            [
                'name' => 'ノートPC',
                'price' => 45000,
                'description' => '高性能なノートパソコン',
                'image' => 'images/Living+Room+Laptop.jpg',
                'condition' => '良好',
                'category' => '家電',
            ],
            [
                'name' => 'マイク',
                'price' => 8000,
                'description' => '高音質のレコーディング用マイク',
                'image' => 'images/Music+Mic+4632231.jpg',
                'condition' => '目立った傷や汚れなし',
                'category' => '家電',
            ],
            [
                'name' => 'ショルダーバッグ',
                'price' => 3500,
                'description' => 'おしゃれなショルダーバッグ',
                'image' => 'images/Purse+fashion+pocket.jpg',
                'condition' => 'やや傷や汚れあり',
                'category' => 'ファッション',
            ],
            [
                'name' => 'タンブラー',
                'price' => 500,
                'description' => '使いやすいタンブラー',
                'image' => 'images/Tumbler+souvenir.jpg',
                'condition' => '状態が悪い',
                'category' => 'キッチン',
            ],
            [
                'name' => 'コーヒーミル',
                'price' => 4000,
                'description' => '手動のコーヒーミル',
                'image' => 'images/Waitress+with+Coffee+Grinder.jpg',
                'condition' => '良好',
                'category' => 'キッチン',
            ],
            [
                'name' => 'メイクセット',
                'price' => 2500,
                'description' => '便利なメイクアップセット',
                'image' => 'images/外出メイクアップセット.jpg',
                'condition' => '目立った傷や汚れなし',
                'category' => 'コスメ',
            ],
        ];

        // マッピング: condition文字列 → statusesテーブルのID
        $statusMap = [
            '良好' => 1,
            '目立った傷や汚れなし' => 2,
            'やや傷や汚れあり' => 3,
            '状態が悪い' => 4,
        ];

        // ユーザーは既にシーディング済みと仮定（全ユーザーからランダムに選ぶ）
        $users = \App\Models\User::all();
        // カテゴリーは、categoriesテーブルの 'category' カラムに保存されていると仮定
        $categories = \App\Models\Category::all()->keyBy('category');

        foreach ($products as $product) {
            // ランダムな出品者を選ぶ
            $userId = $users->random()->id;

            // カテゴリー名から、対応するカテゴリーIDの配列を作成
            $catName = $product['category'];
            $categoryId = $categories->has($catName) ? $categories->get($catName)->id : null;

            // 状態：condition文字列から status_id を取得
            $statusId = $statusMap[$product['condition']];

            Item::create([
                'user_id' => $userId,
                'category_id' => $categoryId,
                'status_id' => $statusId,
                'name' => $product['name'],
                'description' => $product['description'],
                'price' => $product['price'],
                'image' => $product['image'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
