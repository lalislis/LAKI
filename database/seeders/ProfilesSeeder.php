<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfilesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('profiles')->insert([
            'company_id' => '1',
            'user_id' => '1',
            'media_id' => '1',
            'name' => 'John Doe',
            'position' => 'Back End Developer',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('profiles')->insert([
            'company_id' => '2',
            'user_id' => '2',
            'media_id' => '2',
            'name' => 'Jack',
            'position' => 'Front End Developer',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('profiles')->insert([
            'company_id' => '2',
            'user_id' => '3',
            'media_id' => '2',
            'name' => 'Crane',
            'position' => 'Full Stack Developer',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
