<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\User;
use App\Models\Category;
use Carbon\Carbon;

class ItemSeeder extends Seeder
{
    public function run()
    {
        // 現在日時
        $now = Carbon::now();

        // 商品サンプルデータ
        $products = [
            [
                'name'          => '腕時計',
                'price'         => 15000,
                'description'   => 'スタイリッシュなデザインのメンズ腕時計',
                'image'         => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Armani+Mens+Clock.jpg',
                'condition'     => '良好',
                'category'      => 'ファッション',
            ],
            [
                'name'          => 'HDD',
                'price'         => 5000,
                'description'   => '高速で信頼性の高いハードディスク',
                'image'         => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/HDD+Hard+Disk.jpg',
                'condition'     => '目立った傷や汚れなし',
                'category'      => '家電',
            ],
            [
                'name'          => '玉ねぎ3束',
                'price'         => 300,
                'description'   => '新鮮な玉ねぎ3束のセット',
                'image'         => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/iLoveIMG+d.jpg',
                'condition'     => 'やや傷や汚れあり',
                'category'      => 'キッチン',
            ],
            [
                'name'          => '革靴',
                'price'         => 4000,
                'description'   => 'クラシックなデザインの革靴',
                'image'         => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Leather+Shoes+Product+Photo.jpg',
                'condition'     => '状態が悪い',
                'category'      => 'ファッション',
            ],
            [
                'name'          => 'ノートPC',
                'price'         => 45000,
                'description'   => '高性能なノートパソコン',
                'image'         => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Living+Room+Laptop.jpg',
                'condition'     => '良好',
                'category'      => '家電',
            ],
            [
                'name'          => 'マイク',
                'price'         => 8000,
                'description'   => '高音質のレコーディング用マイク',
                'image'         => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Music+Mic+4632231.jpg',
                'condition'     => '目立った傷や汚れなし',
                'category'      => '家電',
            ],
            [
                'name'          => 'ショルダーバッグ',
                'price'         => 3500,
                'description'   => 'おしゃれなショルダーバッグ',
                'image'         => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Purse+fashion+pocket.jpg',
                'condition'     => 'やや傷や汚れあり',
                'category'      => 'ファッション',
            ],
            [
                'name'          => 'タンブラー',
                'price'         => 500,
                'description'   => '使いやすいタンブラー',
                'image'         => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Tumbler+souvenir.jpg',
                'condition'     => '状態が悪い',
                'category'      => 'キッチン',
            ],
            [
                'name'          => 'コーヒーミル',
                'price'         => 4000,
                'description'   => '手動のコーヒーミル',
                'image'         => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Waitress+with+Coffee+Grinder.jpg',
                'condition'     => '良好',
                'category'      => 'キッチン',
            ],
            [
                'name'          => 'メイクセット',
                'price'         => 2500,
                'description'   => '便利なメイクアップセット',
                'image'         => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/%E5%A4%96%E5%87%BA%E3%83%A1%E3%82%A4%E3%82%AF%E3%82%A2%E3%83%83%E3%83%95%E3%82%9A%E3%82%BB%E3%83%83%E3%83%88.jpg',
                'condition'     => '目立った傷や汚れなし',
                'category'      => 'コスメ',
            ],
        ];

        // マッピング: condition文字列 → statusesテーブルのID
        $statusMap = [
            '良好'             => 1,
            '目立った傷や汚れなし' => 2,
            'やや傷や汚れあり'     => 3,
            '状態が悪い'         => 4,
        ];

        // ユーザーは既にシーディング済みと仮定（全ユーザーからランダムに選ぶ）
        $users = \App\Models\User::all();
        // カテゴリーは、categoriesテーブルの 'category' カラムに保存されていると仮定
        $categories = \App\Models\Category::all()->keyBy('category');

        foreach ($products as $product) {
            // ランダムな出品者を選ぶ
            $userId = $users->random()->id;

            // カテゴリー名から対応する Category モデルを取得
            $category = $categories->get($product['category']);

            // 状態：condition文字列から status_id を取得
            $statusId = $statusMap[$product['condition']];

            Item::create([
                'user_id'     => $userId,
                'category_id' => $category->id,
                'status_id'   => $statusId,
                'name'        => $product['name'],
                'description' => $product['description'],
                'price'       => $product['price'],
                'image'       => $product['image'],
                'created_at'  => $now,
                'updated_at'  => $now,
            ]);
        }
    }
}
