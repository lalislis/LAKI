<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MediaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('media')->insert([
            'user_id' => '1',
            'text' => 'John Doe Profile',
            'storage_path' => 'john.png',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('media')->insert([
            'user_id' => '2',
            'text' => 'Jack Profile',
            'storage_path' => 'jack.png',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
