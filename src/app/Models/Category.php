<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'category', // カテゴリ名
    ];

    /**
     * カテゴリに属する商品（1対多）
     */
    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }
}
