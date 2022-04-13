<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompaniesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('companies')->insert([
            'name' => 'PT Laki Nusantara',
            'address' => 'Jakarta',
            'phone' => '021 435 678',
            'email' => 'info@laki.com',
            'website' => 'laki.com',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
