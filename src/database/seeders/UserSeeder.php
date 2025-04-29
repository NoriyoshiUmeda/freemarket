<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

// Userモデルをインポート

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 2件のユーザーを作成する
        User::factory(2)->create();
    }
}
