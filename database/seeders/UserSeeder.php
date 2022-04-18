<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'email' => 'johndoe@gmail.com',
            'password' => bcrypt('rahasia123'),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('users')->insert([
            'email' => 'jack@gmail.com',
            'password' => bcrypt('rahasia123'),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('users')->insert([
            'email' => 'admin@email.com',
            'password' => bcrypt('admin'),
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
