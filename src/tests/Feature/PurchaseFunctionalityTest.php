<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PurchaseFunctionalityTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function logged_in_user_can_complete_a_purchase_and_item_is_marked_sold()
    {
        $user = User::factory()->create();
        $user->profile()->create([
            'postal_code' => '123-4567',
            'address'     => '東京都新宿区',
            'building'    => 'テストビル',
        ]);

        $item = Item::factory()->create();

        $this->actingAs($user)
             ->get(route('purchase.show', ['item_id' => $item->id]))
             ->assertStatus(200)
             ->assertSee('購入する');

        $response = $this->actingAs($user)
                         ->post(route('purchase.execute', ['item_id' => $item->id]), [
                             'payment_method' => 'credit_card',
                             'postal_code'    => $user->profile->postal_code,
                             'address'        => $user->profile->address,
                             'building'       => $user->profile->building,
                         ]);

        $response->assertRedirect(route('mypage', ['tab' => 'buy']));

        $this->assertDatabaseHas('purchases', [
            'user_id'     => $user->id,
            'item_id'     => $item->id,
            'postal_code' => $user->profile->postal_code,
            'address'     => $user->profile->address,
            'building'    => $user->profile->building,
        ]);

        $this->actingAs($user)
             ->get(route('mypage', ['tab' => 'buy']))
             ->assertStatus(200)
             ->assertSee('<p class="sold-label">Sold</p>', false);
    }

    /** @test */
    public function purchased_item_appears_in_user_profile_buy_list()
    {
        $user = User::factory()->create();

        $item = Item::factory()->create();
        Purchase::factory()
                ->for($user, 'user')
                ->for($item, 'item')
                ->create();

        $this->actingAs($user)
             ->get(route('mypage', ['tab' => 'buy']))
             ->assertStatus(200)
             ->assertSeeText($item->name)
             ->assertSee('<p class="sold-label">Sold</p>', false);
    }
}
