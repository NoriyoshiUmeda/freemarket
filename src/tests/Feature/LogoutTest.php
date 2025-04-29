<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authenticated_user_can_logout()
    {
        $user = User::factory()->create();

        // まずログイン
        $this->actingAs($user);

        // POST /logout に叩く（もしくは Route::post('/logout') のルート名を指定）
        $response = $this->post('/logout');

        // ゲスト状態になっていること
        $this->assertGuest();

        // リダイレクト先を確認（トップページやログイン画面など）
        $response->assertRedirect('/');
    }

    /** @test */
    public function guest_cannot_access_logout_route()
    {
        $response = $this->post('/logout');
        $response->assertRedirect('/');
    }
}
