<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseAddressRequest;
use App\Http\Requests\PurchaseRequest;
use App\Models\Item;
use App\Models\Payment;
use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    /**
     * 購入確認画面の表示
     */
    public function showPurchasePage(int $item_id)
    {
        $item    = Item::findOrFail($item_id);
        $user    = Auth::user();
        $profile = $user->profile;

        // 商品IDごとにセッションキーを組み立て
        $keyPostal   = "postal_code_{$item_id}";
        $keyAddress  = "address_{$item_id}";
        $keyBuilding = "building_{$item_id}";

        // フラッシュセッションまたはプロフィール情報を取得
        $postal_code = session()->get($keyPostal,  $profile->postal_code);
        $address     = session()->get($keyAddress, $profile->address);
        $building    = session()->get($keyBuilding, $profile->building);

        return view('purchase.show', compact(
            'item',
            'user',
            'postal_code',
            'address',
            'building'
        ));
    }

    /**
     * 送付先住所変更画面の表示
     */
    public function edit(int $item_id)
    {
        $user    = Auth::user();
        $profile = $user->profile;

        return view('purchase.address_edit', compact('profile', 'item_id'));
    }

    /**
     * 送付先住所更新処理
     */
    public function update(PurchaseAddressRequest $request, int $item_id)
    {
        $data = $request->validated();

        // フラッシュセッションにセット（次のリクエストだけ保持）
        session()->flash("postal_code_{$item_id}", $data['postal_code']);
        session()->flash("address_{$item_id}",   $data['address']);
        session()->flash("building_{$item_id}",  $data['building'] ?? null);

        return redirect()->route('purchase.show', ['item_id' => $item_id]);
    }

    /**
     * 実際の購入処理の実行
     */
    public function executePurchase(PurchaseRequest $request, int $item_id)
    {
        $user = Auth::user();
        $item = Item::findOrFail($item_id);

        // 商品IDごとにキーを再度組み立て
        $keyPostal   = "postal_code_{$item_id}";
        $keyAddress  = "address_{$item_id}";
        $keyBuilding = "building_{$item_id}";

        // フラッシュセッションまたはプロフィール情報を取得
        $postal_code = session()->get($keyPostal,  $user->profile->postal_code);
        $address     = session()->get($keyAddress, $user->profile->address);
        $building    = session()->get($keyBuilding, $user->profile->building);

        // Purchase レコード保存
        $purchase = Purchase::create([
            'user_id'     => $user->id,
            'item_id'     => $item->id,
            'postal_code' => $postal_code,
            'address'     => $address,
            'building'    => $building,
        ]);

        // 支払情報保存
        Payment::create([
            'purchase_id'      => $purchase->id,
            'payment_method'   => $request->input('payment_method'),
            'amount'           => $item->price,
        ]);

        return redirect()->route('mypage', ['tab' => 'buy']);
    }
}
