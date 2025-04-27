<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Item;
use App\Models\Purchase;

class ProductListTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function all_products_are_displayed()
    {
        // 準備：データベースに3つの商品を作成
        $items = Item::factory()->count(3)->create();

        // 操作：商品一覧ページにアクセス
        $response = $this->get('/');

        // 検証：ステータス200で、生成した各商品の名前が表示されていること
        $response->assertStatus(200);
        foreach ($items as $item) {
            $response->assertSeeText($item->name);
        }
    }

    /** @test */
    public function purchased_items_are_marked_sold()
    {
        // 準備：1つの商品を作成し、購入履歴を紐づける
        $item = Item::factory()->create();
        Purchase::factory()->create([
            'item_id' => $item->id,
            // 他に必要なPurchaseのフィールドがあればここに追加
        ]);

        // 操作：商品一覧ページにアクセス
        $response = $this->get('/');

        // 検証：ステータス200で、購入済み商品に「SOLD」ラベルが表示されていること
        $response->assertStatus(200);
        $response->assertSeeText($item->name);
        $response->assertSeeText('Sold');
    }
}
