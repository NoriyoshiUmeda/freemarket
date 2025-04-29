<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);

        // 登録ページのビューを指定
        Fortify::registerView(fn () => view('auth.register'));

        // ログインページのビューを指定
        Fortify::loginView(fn () => view('auth.login'));

        // Fortifyのログイン処理にカスタムバリデーションを適用
        Fortify::authenticateUsing(function (LoginRequest $request) {

            // バリデーション適用
            $validatedData = $request->validated();

            // ユーザー情報取得
            $user = \App\Models\User::where('email', $request->email)->first();

            // ユーザー認証
            if ($user && Hash::check($request->password, $user->password)) {
                return $user;

                // ✅ メール認証が完了していない場合はログイン不可
                if (! $user->hasVerifiedEmail()) {
                    throw ValidationException::withMessages([
                        'email' => ['メール認証が完了していません。確認メールをご確認ください。'],
                    ]);
                }

                return $user;
            }

            // 認証エラー時のエラーメッセージ
            throw ValidationException::withMessages([
                'email' => ['ログイン情報が登録されていません'],
            ]);
        });
    }
}
