<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Http\Requests\ProfileRequest;
use App\Models\Item;
use App\Models\Profile;
use App\Models\purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function update(AddressRequest $request)
    {
        $user = Auth::user();

        // `users` テーブルの `name` を更新
        $user->update(['name' => $request->name]);

        // `profiles` テーブルのデータを取得（なければ新規作成）
        $profile = $user->profile ?: new Profile(['user_id' => $user->id]);

        // **ProfileRequest を手動で取得**
        $profileRequest = app(ProfileRequest::class);

        // 画像アップロード処理
        if ($profileRequest->hasFile('profile_image')) {
            // 古い画像を削除
            if ($profile->profile_image) {
                Storage::delete('public/'.$profile->profile_image);
            }

            // 新しい画像を `storage/app/public/profile_images/` に保存
            $imagePath = $profileRequest->file('profile_image')->store('profile_images', 'public');

            // データベースには画像のファイルパスを保存
            $profile->profile_image = $imagePath;
        }

        // `profiles` テーブルを更新
        $profile->postal_code = $request->postal_code;
        $profile->address = $request->address;
        $profile->building = $request->building;
        $profile->save();

        return redirect()->route('item.index');
    }

    public function edit()
    {
        $user = Auth::user();

        return view('users.edit', compact('user'));
    }

    public function show(Request $request)
    {
        $user = Auth::user(); // ログインユーザー情報を取得

        // クエリパラメータ "tab" を取得
        $tab = $request->input('tab', 'buy');

        if ($tab === 'sell') {
            // 出品した商品一覧
            $items = $user->items()->latest()->get();
            $mode = 'sell';
        } else {
            // それ以外 (tab=buy または 未指定) は購入した商品一覧
            // Userモデルに purchases() リレーションがあり、
            // Purchase から item_id で Itemを取得する想定
            $purchases = $user->purchases()->with('item')->latest()->get();
            $items = $purchases->map(function ($purchase) {
                return $purchase->item;
            });
            $mode = 'buy';
        }

        return view('users.mypage', [
            'user' => $user,
            'mode' => $mode,   // 'buy' or 'sell'
            'items' => $items,  // 購入 or 出品した商品のコレクション
            'request' => $request,
        ]);
    }
}
