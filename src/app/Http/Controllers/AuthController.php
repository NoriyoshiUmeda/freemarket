<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest; // バリデーション用のFormRequest
use App\Http\Requests\RegisterRequest; // バリデーション用のFormRequest
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
        public function showRegisterForm()
    {
        return view('auth.register');
    }

        public function register(RegisterRequest $request)
    {


        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);


        Auth::login($user);

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
            return redirect()->route('item.index');
        }

        return back()->withErrors([
            'email' => 'ログイン情報が登録されていません',
        ]);
    }
}
