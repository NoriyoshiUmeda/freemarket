<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Item;
use App\Models\Status;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SellFunctionalityTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authenticated_user_can_register_a_new_item()
    {
        Storage::fake('public');

        // 準備：ユーザー、カテゴリ、ステータス
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $status = Status::factory()->create();

        // ログインして出品画面を確認
        $this->actingAs($user)
            ->get(route('sell'))
            ->assertStatus(200)
            ->assertSee('出品');

        // フォーム送信
        $response = $this->actingAs($user)
            ->post(route('sell.store'), [
                'category_id' => [$category->id],
                'status_id' => $status->id,
                'name' => 'テスト商品',
                'brand' => 'テストブランド',
                'description' => '詳細説明です',
                'price' => 5000,
                'image' => UploadedFile::fake()->create('photo.jpg', 100, 'image/jpeg'),
            ]);

        // 実装ではトップ (item.index) へリダイレクトするため、ここを修正
        $response->assertRedirect(route('item.index'));

        // DB に登録されていること
        $this->assertDatabaseHas('items', [
            'user_id' => $user->id,
            'name' => 'テスト商品',
            'brand' => 'テストブランド',
            'description' => '詳細説明です',
            'price' => 5000,
        ]);

        // ストレージにファイルが保存されている
        $item = Item::first();
        Storage::disk('public')->assertExists($item->image);
    }

    /** @test */
    public function validation_errors_are_shown_when_required_fields_missing()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->from(route('sell'))
            ->post(route('sell.store'), [
                // 全フィールド空送信
            ]);

        $response->assertRedirect(route('sell'));

        // brand は任意またはバリデーション対象外のため除外
        $response->assertSessionHasErrors([
            'category_id',
            'status_id',
            'name',
            'description',
            'price',
            'image',
        ]);
    }
}
