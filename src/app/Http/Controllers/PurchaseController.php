<?php

namespace App\Http\Controllers;

// プロフィール編集で使用しているリクエスト
use App\Http\Requests\PurchaseAddressRequest;
use App\Http\Requests\PurchaseRequest;
use App\Models\Item;
use App\Models\Payment;
use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    public function showPurchasePage($item_id)
    {
        // 商品をIDで取得（存在しなければ404エラー）
        $item = Item::findOrFail($item_id);
        $user = Auth::user();

        // 購入画面のビューに商品情報を渡す
        return view('purchase.show', compact('item', 'user'));
    }

    // POST: 購入処理の実行（PurchaseRequest を利用）
    public function executePurchase(PurchaseRequest $request, $item_id)
    {
        $user = auth()->user();
        $item = Item::findOrFail($item_id);
        $data = $request->validated();

        // セッションに保存されている配送先情報を取得（なければユーザーのプロフィール情報をフォールバック）
        $postal_code = session('postal_code', $user->profile->postal_code);
        $address = session('address', $user->profile->address);
        $building = session('building', $user->profile->building);

        // 購入レコードの保存（Purchaseテーブルに郵便番号、住所、建物情報を登録）
        $purchase = Purchase::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'postal_code' => $postal_code,
            'address' => $address,
            'building' => $building,
        ]);

        // 支払方法の保存（今回はフォームからの選択）
        Payment::create([
            'purchase_id' => $purchase->id,
            'payment_method' => $data['payment_method'],
            'stripe_payment_id' => 'dummy_'.uniqid(), // 一意なダミーIDを生成
            'amount' => $item->price,
        ]);

        // 購入完了後、使用済みのセッション配送先情報を削除
        session()->forget(['postal_code', 'address', 'building']);

        return redirect()->route('mypage', ['tab' => 'buy']);
    }

    /**
     * 送付先住所変更画面の表示
     *
     * @param  int  $item_id
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
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $item_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(PurchaseAddressRequest $request, $item_id)
    {

        // PurchaseAddressRequest でバリデーション済みのデータを取得
        $data = $request->validated();

        // ユーザーの配送先情報をセッションに保存（プロフィールは更新しない）
        session([
            'postal_code' => $data['postal_code'],
            'address' => $data['address'],
            'building' => $data['building'] ?? null,
        ]);

        // 更新後、購入画面にリダイレクト
        return redirect()->route('purchase.show', ['item_id' => $item_id]);
    }
}
