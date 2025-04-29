<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'status_id',
        'name',
        'description',
        'price',
        'image',
        'brand',
    ];

    /**
     * 出品者（ユーザー）とのリレーション
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * カテゴリとのリレーション
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * ステータスとのリレーション
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    /**
     * 購入履歴とのリレーション（1対1）
     */
    public function purchase(): HasOne
    {
        return $this->hasOne(Purchase::class, 'item_id');
    }

    /**
     * お気に入りとのリレーション（1対多）
     */
    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    /**
     * コメントとのリレーション（1対多）
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
