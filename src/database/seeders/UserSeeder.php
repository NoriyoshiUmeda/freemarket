<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;  // Userモデルをインポート

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
