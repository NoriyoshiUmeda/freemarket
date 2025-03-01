<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', // コメント投稿者のID
        'item_id', // コメントが紐づく商品のID
        'comment', // コメント本文
    ];

    /**
     * コメントを投稿したユーザーとのリレーション（多対1）
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * コメントが紐づく商品とのリレーション（多対1）
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
