<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Item;
use App\Models\Favorite;
use Tests\TestCase;

class MyListTest extends TestCase
{
    use RefreshDatabase;

    /**
     * ゲスト（未ログイン）はマイリストタブにアクセスしても
     * アイテムが何も表示されない
     *
     * @return void
     */
    public function test_guest_sees_no_items_in_mylist()
    {
        // テスト用アイテムをいくつか作成しておく
        $items = Item::factory()->count(2)->create();

        // マイリストタブにアクセス
        $response = $this->get(route('item.index', ['tab' => 'mylist']));

        // ステータスは 200 OK
        $response->assertStatus(200);

        // 作成したアイテム名が表示されていないこと
        $response->assertDontSeeText($items[0]->name);
        $response->assertDontSeeText($items[1]->name);
    }

    /**
     * ログインユーザーは自分のお気に入りだけが表示される
     *
     * @return void
     */
    public function test_authenticated_user_sees_only_their_favorites()
    {
        // テスト用ユーザーを２人作成
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        // アイテムを３つ作成
        $items = Item::factory()->count(3)->create();

        // userA は items[0], items[1] をお気に入りに
        Favorite::factory()->create([
            'user_id' => $userA->id,
            'item_id' => $items[0]->id,
        ]);
        Favorite::factory()->create([
            'user_id' => $userA->id,
            'item_id' => $items[1]->id,
        ]);

        // userB は items[2] をお気に入りに
        Favorite::factory()->create([
            'user_id' => $userB->id,
            'item_id' => $items[2]->id,
        ]);

        // userA でログイン
        $this->actingAs($userA);

        // マイリストタブにアクセス
        $response = $this->get(route('item.index', ['tab' => 'mylist']));

        // ステータスは 200 OK
        $response->assertStatus(200);

        // userA がお気に入りにした２つのアイテム名が表示される
        $response->assertSeeText($items[0]->name);
        $response->assertSeeText($items[1]->name);

        // userA が登録していない items[2] は表示されない
        $response->assertDontSeeText($items[2]->name);
    }
}
