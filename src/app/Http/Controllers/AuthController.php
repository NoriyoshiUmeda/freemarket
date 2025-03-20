<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest; // バリデーション用のFormRequest
use App\Http\Requests\LoginRequest; // バリデーション用のFormRequest
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * 会員登録画面の表示
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * 会員登録処理
     */
    public function register(RegisterRequest $request)
    {

        // ユーザー作成
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

      
        return redirect()->route('profile.edit'); 
    }

    public function showLoginForm()
    {
        return view('auth.login'); // ログイン画面を表示
    }

    public function login(LoginRequest $request)
{
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $user = Auth::user();

        if ($user->is_first_login) {
            // 初回ログイン時は `is_first_login` を false に更新し、プロフィール編集画面へリダイレクト
            $user->update(['is_first_login' => false]);
            Auth::setUser($user->fresh());
            return redirect()->route('profile.edit');
        }

        // 2回目以降のログインは商品一覧画面へリダイレクト
        return redirect()->route('item.index');
    }

    return back()->withErrors([
        'email' => 'ログイン情報が登録されていません',
    ]);
}

}