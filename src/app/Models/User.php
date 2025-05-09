<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory;
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_first_login',
    ];

        public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }

        public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }

        public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
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
