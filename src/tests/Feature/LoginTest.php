<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * メールアドレスが空だとバリデーションエラー
     */
    public function test_email_is_required_on_login()
    {
        $response = $this->post('/login', [
            'email' => '',
            'password' => 'any-password',
        ]);

        $response
            ->assertSessionHasErrors(['email'])
            ->assertSessionHasErrors([
                'email' => 'メールアドレスを入力してください',
            ]);
    }

    /**
     * パスワードが空だとバリデーションエラー
     */
    public function test_password_is_required_on_login()
    {
        $response = $this->post('/login', [
            'email' => 'user@example.com',
            'password' => '',
        ]);

        $response
            ->assertSessionHasErrors(['password'])
            ->assertSessionHasErrors([
                'password' => 'パスワードを入力してください',
            ]);
    }

    /**
     * 存在しない（または誤った）資格情報だとログイン失敗
     */
    public function test_cannot_login_with_invalid_credentials()
    {
        // 事前にユーザーを作成しておく
        User::factory()->create([
            'email' => 'user@example.com',
            'password' => bcrypt('correct-password'),
        ]);

        // from() を付けると、リダイレクト元が /login になる
        $response = $this->from('/login')->post('/login', [
            'email' => 'user@example.com',
            'password' => 'wrong-password',
        ]);

        $response
            ->assertRedirect('/login')
            ->assertSessionHasErrors([
                // LoginController で設定したカスタムメッセージに合わせる
                'email' => 'ログイン情報が登録されていません',
            ]);
    }

    /**
     * 正しい資格情報ならログイン成功＋指定画面へリダイレクト
     */
    public function test_successful_login_redirects_to_profile()
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'user@example.com',
            'password' => 'password123',
        ]);

        // 認証済みになっていること
        $this->assertAuthenticatedAs($user);

        // ログイン後は商品一覧画面へリダイレクト
        $response->assertRedirect('/');
    }
}
