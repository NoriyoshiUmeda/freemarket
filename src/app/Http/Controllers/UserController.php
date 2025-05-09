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


        $user->update(['name' => $request->name]);


        $profile = $user->profile ?: new Profile(['user_id' => $user->id]);


        $profileRequest = app(ProfileRequest::class);


        if ($profileRequest->hasFile('profile_image')) {

            if ($profile->profile_image) {
                Storage::delete('public/'.$profile->profile_image);
            }


            $imagePath = $profileRequest->file('profile_image')->store('profile_images', 'public');


            $profile->profile_image = $imagePath;
        }


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


        $tab = $request->input('tab', 'buy');

        if ($tab === 'sell') {

            $items = $user->items()->latest()->get();
            $mode = 'sell';
        } else {



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
