<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Favorite;
use App\Models\Item;
use App\Models\Status;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductDetailTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function product_detail_displays_all_required_information()
    {
        // 1) ユーザー・ステータス・カテゴリを作成
        $owner = User::factory()->create();
        $status = Status::factory()->create(['name' => '新品']);
        $category = Category::factory()->create(['category' => 'テストカテゴリ']);

        // 2) 商品を作成（category_id を直接指定）
        $item = Item::factory()->create([
            'user_id' => $owner->id,
            'status_id' => $status->id,
            'category_id' => $category->id,
            'name' => 'Test Item',
            'brand' => 'Test Brand',
            'price' => 12345,
            'description' => '詳細説明',
        ]);

        // 3) コメントを２つ作成
        $commentUser = User::factory()->create();
        $comments = Comment::factory()->count(2)
            ->for($commentUser, 'user')
            ->for($item, 'item')
            ->create(['comment' => 'サンプルコメント']);

        // 4) いいねを１件作成
        Favorite::factory()
            ->for($commentUser, 'user')
            ->for($item, 'item')
            ->create();

        // — 商品詳細ページへアクセス
        $response = $this->get(route('item.show', ['item_id' => $item->id]));
        $response->assertStatus(200);

        // — 商品名・ブランド・価格・説明・ステータス
        $response->assertSeeText('Test Item')
            ->assertSeeText('Test Brand')
            ->assertSeeText('¥12,345')
            ->assertSeeText('詳細説明')
            ->assertSeeText('新品');

        // — カテゴリ名
        $response->assertSeeText('テストカテゴリ');

        // — いいね数(1) とコメント数(2)
        $response->assertSeeText('1')              // like count
            ->assertSeeText('コメント (2)');  // comment title

        // — 各コメントのユーザー名と本文
        foreach ($comments as $comment) {
            $response->assertSeeText($comment->user->name)
                ->assertSeeText($comment->comment);
        }
    }

    /** @test */
    public function product_detail_shows_associated_category()
    {
        // 単一カテゴリでの表示テスト
        $category = Category::factory()->create(['category' => 'Soloカテゴリ']);
        $item = Item::factory()->create(['category_id' => $category->id]);

        $response = $this->get(route('item.show', ['item_id' => $item->id]));
        $response->assertStatus(200)
            ->assertSeeText('Soloカテゴリ');
    }
}
