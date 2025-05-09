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

        public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

        public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

        public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

        public function purchase(): HasOne
    {
        return $this->hasOne(Purchase::class, 'item_id');
    }

        public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

        public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
