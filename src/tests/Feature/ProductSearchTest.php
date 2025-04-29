<?php

namespace Tests\Feature;

use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductSearchTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 検索キーワードにマッチする商品がひとつもないとき、
     * ビューに渡された items が空のコレクションであり、
     * HTML上に商品カード（.item-card）が一切出力されないこと
     *
     * @return void
     */
    public function test_search_returns_empty_collection_when_no_match()
    {
        // テスト用の商品を２件作成
        Item::factory()->create(['name' => 'test1']);
        Item::factory()->create(['name' => 'test2']);

        // 存在しないキーワードで検索
        $response = $this->get(route('item.index', ['search' => 'nothing']));

        $response->assertStatus(200)
            // ビューの items が空コレクションであること
            ->assertViewHas('items', function ($items) {
                return $items->isEmpty();
            })
            // HTML に商品カード要素が一切出ないこと
            ->assertDontSee('class="item-card"');
    }

    /**
     * 完全一致でヒットする商品だけが表示されることを検証
     *
     * @return void
     */
    public function test_exact_match_returns_only_matching_items()
    {
        // 完全一致でヒットさせる item と、ヒットさせない item
        Item::factory()->create(['name' => 'test1']);
        Item::factory()->create(['name' => 'test2']);

        // 'test1' で検索
        $response = $this->get(route('item.index', ['search' => 'test1']));

        $response->assertStatus(200)
            ->assertSeeText('test1')
            ->assertDontSeeText('test2');
    }

    /**
     * 部分一致かつ大文字小文字を区別せずマッチすることを検証
     *
     * @return void
     */
    public function test_partial_and_case_insensitive_match()
    {
        // 大文字小文字混在の商品名を用意
        Item::factory()->create(['name' => 'TestOne']);
        Item::factory()->create(['name' => 'testonePlus']);
        Item::factory()->create(['name' => 'OtherItem']);

        // 小文字で検索してもマッチすること
        $response = $this->get(route('item.index', ['search' => 'testone']));

        $response->assertStatus(200)
            ->assertSeeText('TestOne')
            ->assertSeeText('testonePlus')
            ->assertDontSeeText('OtherItem');
    }
}
