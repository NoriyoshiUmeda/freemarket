<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', // ユーザーID
        'profile_image', // プロフィール画像
        'postal_code', // 郵便番号
        'address', // 住所
        'building', // 建物名
        'email_verified_at', // メール認証日時
    ];

    /**
     * ユーザーとのリレーション（1対1）
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
