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
        public function showPurchasePage(int $item_id)
    {
        $item    = Item::findOrFail($item_id);
        $user    = Auth::user();
        $profile = $user->profile;


        $keyPostal   = "postal_code_{$item_id}";
        $keyAddress  = "address_{$item_id}";
        $keyBuilding = "building_{$item_id}";


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

        public function edit(int $item_id)
    {
        $user    = Auth::user();
        $profile = $user->profile;

        return view('purchase.address_edit', compact('profile', 'item_id'));
    }

        public function update(PurchaseAddressRequest $request, int $item_id)
    {
        $data = $request->validated();


        session()->flash("postal_code_{$item_id}", $data['postal_code']);
        session()->flash("address_{$item_id}",   $data['address']);
        session()->flash("building_{$item_id}",  $data['building'] ?? null);

        return redirect()->route('purchase.show', ['item_id' => $item_id]);
    }

       public function executePurchase(PurchaseRequest $request, int $item_id)
{
    $purchase = Purchase::create([
        'user_id'     => Auth::id(),
        'item_id'     => $item_id,
        'postal_code' => $request->input('postal_code'),
        'address'     => $request->input('address'),
        'building'    => $request->input('building'),
    ]);

    Payment::create([
        'purchase_id'    => $purchase->id,
        'payment_method' => $request->input('payment_method'),
        'amount'         => Item::findOrFail($item_id)->price,
    ]);

    return redirect()->route('mypage', ['tab' => 'buy']);
}

}
