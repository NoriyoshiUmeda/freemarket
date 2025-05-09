<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Status extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', // ステータス名（例: "出品中", "売却済み"）
    ];

        public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }
}
