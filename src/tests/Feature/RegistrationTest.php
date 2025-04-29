<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 名前が空だとバリデーションエラー
     *
     * @return void
     */
    public function test_name_is_required()
    {
        $response = $this->post('/register', [
            'name' => '',
            'email' => 'user@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response
            ->assertSessionHasErrors(['name'])
            ->assertSessionHasErrors([
                'name' => 'お名前を入力してください',
            ]);
    }

    /**
     * メールアドレスが空だとバリデーションエラー
     *
     * @return void
     */
    public function test_email_is_required()
    {
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => '',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response
            ->assertSessionHasErrors(['email'])
            ->assertSessionHasErrors([
                'email' => 'メールアドレスを入力してください',
            ]);
    }

    /**
     * パスワードが空だとバリデーションエラー
     *
     * @return void
     */
    public function test_password_is_required()
    {
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'user@example.com',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response
            ->assertSessionHasErrors(['password'])
            ->assertSessionHasErrors([
                'password' => 'パスワードを入力してください',
            ]);
    }

    /**
     * パスワードが7文字以下だとバリデーションエラー
     *
     * @return void
     */
    public function test_password_must_be_at_least_8_characters()
    {
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'user@example.com',
            'password' => 'short7',
            'password_confirmation' => 'short7',
        ]);

        $response
            ->assertSessionHasErrors(['password'])
            ->assertSessionHasErrors([
                'password' => 'パスワードは8文字以上で入力してください',
            ]);
    }

    /**
     * パスワード確認が一致しないとバリデーションエラー
     *
     * @return void
     */
    public function test_password_and_confirmation_must_match()
    {
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'user@example.com',
            'password' => 'password123',
            'password_confirmation' => 'different123',
        ]);

        $response
            ->assertSessionHasErrors(['password'])
            ->assertSessionHasErrors([
                'password' => 'パスワードと一致しません',
            ]);
    }

    /**
     * 正常に登録できたら DB に保存され、/login にリダイレクト
     *
     * @return void
     */
    public function test_successful_registration_redirects_to_login_and_creates_user()
    {
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'user@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // DB にユーザーが保存されていること
        $this->assertDatabaseHas('users', [
            'email' => 'user@example.com',
        ]);

        // プロフィール編集画面へリダイレクトされること
        $response->assertRedirect('/mypage/profile');
    }
}
