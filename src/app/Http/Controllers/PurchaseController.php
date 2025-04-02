<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AddressRequest; // プロフィール編集で使用しているリクエスト
use App\Models\Item;
use App\Models\Purchase;
use App\Models\Profile;
use App\Models\Payment;
use App\Http\Requests\PurchaseAddressRequest;
use App\Http\Requests\PurchaseRequest;

use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    public function showPurchasePage($item_id)
    {
        // 商品をIDで取得（存在しなければ404エラー）
        $item = Item::findOrFail($item_id);
        $user = Auth::user();

        // 購入画面のビューに商品情報を渡す
        return view('purchase.show', compact('item','user'));
    }

         // POST: 購入処理の実行（PurchaseRequest を利用）
    public function executePurchase(PurchaseRequest $request, $item_id)
    {
    $user = auth()->user();
    $item = Item::findOrFail($item_id);
    $data = $request->validated();

    // プロフィールから配送先住所を取得
    $shippingAddress = $user->profile->address;

    // 購入レコードを保存
    $purchase = Purchase::create([
        'user_id' => $user->id,
        'item_id' => $item->id,
        'address' => $shippingAddress,
    ]);

    // 支払方法の保存（今回はフォームからの選択）
    Payment::create([
        'purchase_id' => $purchase->id,
        'payment_method' => $data['payment_method'], 
        'stripe_payment_id' => 'dummy_' . uniqid(), // 一意なダミーIDを生成
        'amount' => $item->price,
    ]);

    return redirect()->route('purchase.show', ['item_id' => $item->id])
                     ->with('success', '購入が完了しました！');
    }
    /**
     * 送付先住所変更画面の表示
     *
     * @param int $item_id
     * @return \Illuminate\View\View
     */
    public function edit($item_id)
    {
         // ログインユーザーのプロフィールを取得
        $user = auth()->user();
        $profile = $user->profile; // リレーションが定義されている前提
        
        return view('purchase.address_edit', compact('profile', 'item_id'));
    }

    /**
     * 送付先住所の更新処理
     *
     * @param \Illuminate\Http\Request $request
     * @param int $item_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(PurchaseAddressRequest $request, $item_id)
{

    // ユーザーのプロフィールを取得
    $user = auth()->user();
    $profile = $user->profile; // Profileモデルとのリレーションが定義されている前提

    // AddressRequestでバリデーション済みのデータを取得
    $data = $request->validated();

      // プロフィールを更新（ここでは郵便番号、住所、建物名など）
    $profile->update($data);
    $profile->save();


    // 更新後、購入画面にリダイレクト
    return redirect()->route('purchase.show', ['item_id' => $item_id]);
}

}
