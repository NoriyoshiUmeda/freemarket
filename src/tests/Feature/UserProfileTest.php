<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;

class UserProfileTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function mypage_shows_profile_and_item_lists()
    {
        $user      = User::factory()->create(['name' => 'Taro']);
        $sellItem  = Item::factory()->create(['user_id' => $user->id, 'name' => 'SellItem']);
        $buyItem   = Item::factory()->create(['name' => 'BuyItem']);
        Purchase::factory()->for($user, 'user')->for($buyItem, 'item')->create();

        $this->actingAs($user);

        // 出品タブ
        $this->get(route('mypage', ['tab' => 'sell']))
             ->assertStatus(200)
             ->assertSeeText('Taro')
             ->assertSeeText('SellItem')
             ->assertDontSeeText('BuyItem');

        // 購入タブ
        $this->get(route('mypage', ['tab' => 'buy']))
             ->assertStatus(200)
             ->assertSeeText('BuyItem')
             ->assertSee('<p class="sold-label">Sold</p>', false);
    }

    /** @test */
    public function user_can_update_profile_information()
    {
        $user = User::factory()->create();
        // プロフィール初期値
        $user->profile()->create([
            'postal_code' => '000-0000',
            'address'     => '旧住所',
            'building'    => '旧ビル',
        ]);

        // プロファイル更新リクエスト
        $response = $this->actingAs($user)
             ->from(route('profile.edit'))
             ->put(route('profile.update'), [
                 'name'        => 'NewName',
                 'postal_code' => '111-1111',
                 'address'     => '新宿区',
                 'building'    => '新ビル',
             ]);

        // ホーム (item.index) へのリダイレクトを期待
        $response->assertRedirect(route('item.index'));

        // DB に更新内容が反映されていること
        $this->assertDatabaseHas('users', [
            'id'   => $user->id,
            'name' => 'NewName',
        ]);
        $this->assertDatabaseHas('profiles', [
            'user_id'     => $user->id,
            'postal_code' => '111-1111',
            'address'     => '新宿区',
            'building'    => '新ビル',
        ]);

        // 編集画面でフォームに新しい値が入っているかチェック
        $this->actingAs($user)
             ->get(route('profile.edit'))
             ->assertSee('value="NewName"', false)
             ->assertSee('value="111-1111"', false)
             ->assertSee('value="新宿区"', false)
             ->assertSee('value="新ビル"', false);
    }
}
