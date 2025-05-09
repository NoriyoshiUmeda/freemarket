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

        public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

        public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

        public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }
}
