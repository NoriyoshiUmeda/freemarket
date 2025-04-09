<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', // 購入者ID
        'item_id', // 購入された商品ID
        'postal_code',
        'address',
        'building', 
    ];

    /**
     * 購入者（ユーザー）とのリレーション(多対1)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 購入された商品とのリレーション(1対1)
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * 支払い情報とのリレーション（1対1）
     */
    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }
}
