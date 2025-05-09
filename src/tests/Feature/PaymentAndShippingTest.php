<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentAndShippingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function selecting_payment_method_reflects_immediately_on_purchase_page()
    {
        $user = User::factory()->create();
        Profile::factory()->create(['user_id' => $user->id]);

        $item = Item::factory()->create();

        $this->actingAs($user)
            ->get(route('purchase.show', [
                'item_id' => $item->id,
                'payment_method' => 'convenience_store',
            ]))
            ->assertStatus(200)
             // 右カラム .method-row に「コンビ二支払い」が表示されている
            ->assertSeeText('コンビニ支払い');
    }

    /** @test */
    public function updating_shipping_address_reflects_on_purchase_page()
    {
        $user = User::factory()->create();
        Profile::factory()->create(['user_id' => $user->id]);

        $item = Item::factory()->create();

        $this->actingAs($user)
            ->put(route('purchase.address.update', ['item_id' => $item->id]), [
                'postal_code' => '987-6543',
                'address' => '大阪市中央区',
                'building' => 'ビルA',
            ])->assertRedirect(); // リダイレクト確認

        $this->actingAs($user)
            ->get(route('purchase.show', ['item_id' => $item->id]))
            ->assertStatus(200)
             // 左カラム配送先に正しく反映
            ->assertSeeText('〒987-6543')
            ->assertSeeText('大阪市中央区')
            ->assertSeeText('ビルA');
    }
}
