<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_id', // 購入ID
        'stripe_payment_id', // Stripe決済ID
        'payment_method', // 支払い方法（クレジットカード or コンビニ）
        'amount'
    ];

    /**
     * 支払い情報が紐づく購入履歴（1対1）
     */
    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class);
    }
}
