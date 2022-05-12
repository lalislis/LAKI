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
        $factoryCount = 10;
        for ($i = 0; $i < $factoryCount; $i++) {
            $media = Media::create(['storage_path' => Media::DEFAULT_COMPANY]);

            Companies::factory()->create([
                'media_id' => $media->id,
            ]);
        }
    }
}
