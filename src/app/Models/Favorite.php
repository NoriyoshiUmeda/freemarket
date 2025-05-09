<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Favorite extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', // ユーザーID
        'item_id', // 商品ID
    ];

        public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

        public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
