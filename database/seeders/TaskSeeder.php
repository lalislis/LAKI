<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tasks')->insert([
            'title' => 'Task 1',
            'user_id' => 3,
            'body' => 'Task 1 description',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('tasks')->insert([
            'title' => 'Task 2',
            'user_id' => 3,
            'body' => 'Task 2 description',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
