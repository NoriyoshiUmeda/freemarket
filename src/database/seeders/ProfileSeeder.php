<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Profile;

class ProfileSeeder extends Seeder
{
        public function run(): void
    {

        Profile::create([
            'user_id'     => 1,
            'postal_code' => '123-4567',
            'address'     => '渋谷区神南',
            'building'    => 'Aビル',
        ]);


        Profile::create([
            'user_id'     => 2,
            'postal_code' => '234-5678',
            'address'     => '北区梅田',
            'building'    => 'Bマンション',
        ]);
    }
}
