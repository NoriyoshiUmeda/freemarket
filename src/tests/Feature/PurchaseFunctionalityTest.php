<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;

class PurchaseFunctionalityTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function logged_in_user_can_complete_a_purchase_and_item_is_marked_sold()
    {
        // ユーザー作成＋プロフィール登録
        $user = User::factory()->create();
        $user->profile()->create([
            'postal_code' => '123-4567',
            'address'     => '東京都新宿区',
            'building'    => 'テストビル',
        ]);

        // 商品作成
        $item = Item::factory()->create();

        // 購入画面表示
        $this->actingAs($user)
             ->get(route('purchase.show', ['item_id' => $item->id]))
             ->assertStatus(200)
             ->assertSee('購入する');

        // 購入実行
        $response = $this->post(route('purchase.execute', ['item_id' => $item->id]), [
            'payment_method' => 'credit_card',
        ]);

        // 購入後はマイページ buy タブへリダイレクト
        $response->assertRedirect(route('mypage', ['tab' => 'buy']));

        // purchases テーブルに買い物が記録されている
        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        // マイページ購入タブで Sold ラベルが表示されている
        $this->actingAs($user)
             ->get(route('mypage', ['tab' => 'buy']))
             ->assertStatus(200)
             ->assertSee('<p class="sold-label">Sold</p>', false);
    }

    /** @test */
    public function purchased_item_appears_in_user_profile_buy_list()
    {
        $user = User::factory()->create();

        // 既存購入レコードを作成
        $item = Item::factory()->create();
        Purchase::factory()->for($user, 'user')->for($item, 'item')->create();

        // マイページ購入タブで該当商品と Sold ラベルを確認
        $this->actingAs($user)
             ->get(route('mypage', ['tab' => 'buy']))
             ->assertStatus(200)
             ->assertSeeText($item->name)
             ->assertSee('<p class="sold-label">Sold</p>', false);
    }
}
