<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PurchaseController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 会員登録
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// ログイン（未認証ユーザーがアクセスできるように `auth` ミドルウェアの外に定義）
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']); // ← ここは必須

//商品一覧画面
Route::get('/', [ItemController::class, 'index'])->name('item.index');

// ユーザ認証不要：商品詳細画面表示
Route::get('/item/{item_id}', [ItemController::class, 'show'])->name('item.show');

// 認証が必要なページ（auth ミドルウェアを適用）
Route::middleware(['auth'])->group(function () {
    Route::get('/mypage', [UserController::class, 'show'])->name('mypage');
    Route::get('/mypage/profile', [UserController::class, 'edit'])->name('profile.edit');
    Route::put('/mypage/profile', [UserController::class, 'update'])->name('profile.update');
    Route::get('/sell', [ItemController::class, 'create'])->name('sell');
    Route::post('/sell', [ItemController::class, 'store']);

    Route::get('/purchase/{item_id}', [PurchaseController::class, 'showPurchasePage'])->name('purchase');
    Route::get('/purchase/address/{item_id}', [PurchaseController::class, 'edit']);
    Route::put('/purchase/address/{item_id}', [PurchaseController::class, 'update']);

    Route::post('/item/{item_id}/like', [ItemController::class, 'like'])->name('item.like');
    Route::delete('/item/{item_id}/unlike', [ItemController::class, 'unlike'])->name('item.unlike');
    Route::post('/item/{item_id}/comment', [ItemController::class, 'addComment'])->name('item.comment');
});
