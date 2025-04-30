<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 一般ユーザー1
        User::create([
            'name' => 'User One',
            'email' => 'user1@example.com',
            'password' => Hash::make('password'), // パスワードはハッシュ化
        ]);

        // 一般ユーザー2
        User::create([
            'name' => 'User Two',
            'email' => 'user2@example.com',
            'password' => Hash::make('password'),
        ]);
    }
}
