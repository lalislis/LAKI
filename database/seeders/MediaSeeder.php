<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MediaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('profiles')->insert([
            'user_id' => '1',
            'text' => 'John Doe Profile',
            'storagepath' => 'john.png',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('profiles')->insert([
            'user_id' => '2',
            'text' => 'Jack Profile',
            'storagepath' => 'jack.png',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
