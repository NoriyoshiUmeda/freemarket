<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentFunctionalityTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function logged_in_user_can_post_a_comment()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        // POST /item/{item_id}/comment
        $this->actingAs($user)
            ->post(route('item.comment', ['item_id' => $item->id]), [
                'comment' => '素晴らしい商品です！',
            ]);

        // DB へ保存
        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'comment' => '素晴らしい商品です！',
        ]);

        // 詳細ページの見出しに コメント (1) が含まれる
        $response = $this->get(route('item.show', ['item_id' => $item->id]));
        $response->assertSeeText('コメント (1)');
    }

    /** @test */
    public function guest_cannot_post_a_comment()
    {
        $item = Item::factory()->create();

        $response = $this->post(
            route('item.comment', ['item_id' => $item->id]),
            ['comment' => 'NG']
        );

        // ログイン画面へリダイレクト
        $response->assertRedirect(route('login'));
        $this->assertDatabaseCount('comments', 0);
    }

    /** @test */
    public function empty_comment_triggers_validation_error()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $response = $this->actingAs($user)
            ->from(route('item.show', ['item_id' => $item->id]))
            ->post(route('item.comment', ['item_id' => $item->id]), [
                'comment' => '',
            ]);

        $response->assertRedirect(route('item.show', ['item_id' => $item->id]));
        $response->assertSessionHasErrors('comment');
    }

    /** @test */
    public function overly_long_comment_triggers_validation_error()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $long = str_repeat('あ', 256);
        $response = $this->actingAs($user)
            ->from(route('item.show', ['item_id' => $item->id]))
            ->post(route('item.comment', ['item_id' => $item->id]), [
                'comment' => $long,
            ]);

        $response->assertRedirect(route('item.show', ['item_id' => $item->id]));
        $response->assertSessionHasErrors('comment');
    }
}
