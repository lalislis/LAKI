<?php

namespace Database\Seeders;

use App\Models\Companies;
use App\Models\Media;
use Illuminate\Database\Seeder;

class CompaniesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (Media::all()->pluck('id') as $id) {
            Companies::factory(1)->create([
                'media_id' => $id,
            ]);
        }
    }
}
