<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LikeFunctionalityTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function logged_in_user_can_like_an_item_and_count_increases()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        // いいねをPOST
        $this->actingAs($user)
            ->post(route('item.like', ['item_id' => $item->id]));

        // DB に保存されていること
        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        // 詳細ページ取得
        $response = $this->get(route('item.show', ['item_id' => $item->id]));

        $response->assertStatus(200)
                 // <span class="icon-count">1</span> が生のまま含まれること
            ->assertSee('<span class="icon-count">1</span>', false)
                 // ボタンに liked クラスが付いていることもチェック
            ->assertSee('class="like-btn liked"', false);
    }

    /** @test */
    public function like_icon_changes_state_after_liking()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user)
            ->post(route('item.like', ['item_id' => $item->id]));

        $response = $this->get(route('item.show', ['item_id' => $item->id]));

        $response->assertStatus(200)
                 // 生のクラス属性として liked が付与されているか
            ->assertSee('class="like-btn liked"', false);
    }

    /** @test */
    public function clicking_like_again_unlikes_and_count_decreases()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        // 1回目いいね
        $this->actingAs($user)
            ->post(route('item.like', ['item_id' => $item->id]));
        // 2回目で解除 (DELETE リクエスト)
        $this->actingAs($user)
            ->delete(route('item.unlike', ['item_id' => $item->id]));

        // DB レコードが消えていること
        $this->assertDatabaseMissing('favorites', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->get(route('item.show', ['item_id' => $item->id]));

        $response->assertStatus(200)
                 // いいね数のスパンがなくなっていることをチェック
            ->assertDontSee('<span class="icon-count">1</span>', false);
    }
}
