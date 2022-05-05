<?php

namespace Database\Seeders;

use App\Models\Companies;
use App\Models\Media;
use App\Models\Profiles;
use App\Models\User;
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
        Companies::all()->pluck('id')->each(function ($companyId) {
            $users = User::factory(3)->create();
            $users->each(function ($user) use ($companyId) {
                Profiles::factory()->create([
                    'company_id' => $companyId,
                    'user_id' => $user->id,
                    'media_id' => random_int(1, Media::all()->count())
                ]);
            });

            $superUser = User::factory()->create(['role' => 2]);
            Profiles::factory()->create([
                'company_id' => $companyId,
                'user_id' => $superUser->id,
                'media_id' => random_int(1, Media::all()->count()),
            ]);
        });

        $admin = User::factory()->create(['role' => 3]);
        Profiles::factory()->create([
            'company_id' => 1,
            'user_id' => $admin->id,
            'media_id' => 1,
        ]);
    }
}
