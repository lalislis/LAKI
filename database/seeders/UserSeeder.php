<?php

namespace Database\Seeders;

use App\Models\Companies;
use App\Models\Media;
use App\Models\Profiles;
use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->faker = Faker::create();
        Companies::all()->pluck('id')->each(function ($companyId) {
            $factoryUser = 5;
            for ($i = 0; $i < $factoryUser; $i++) {
                $firstName = $this->faker->firstName();
                $lastName = $this->faker->lastName();
                $user = User::create([
                    'email' =>
                    str_replace(' ', '', strtolower($firstName)) .
                        '.' .
                        str_replace(' ', '', strtolower($lastName)) .
                        '@' .
                        $this->faker->domainName(),

                    'password' => Hash::make('password'),
                    'role' => 1,
                ]);

                $media = Media::create([
                    'storage_path' => Media::DEFAULT_USER,
                ]);

                Profiles::create([
                    'name' => $firstName . ' ' . $lastName,
                    'position' => $this->faker->jobTitle(),
                    'company_id' => $companyId,
                    'user_id' => $user->id,
                    'media_id' => $media->id,
                ]);
            }


            $factorySuperUser = 2;
            for ($i = 0; $i < $factorySuperUser; $i++) {
                $firstName = $this->faker->firstName();
                $lastName = $this->faker->lastName();
                $user = User::create([
                    'email' =>
                    str_replace(' ', '', strtolower($firstName)) .
                        '.' .
                        str_replace(' ', '', strtolower($lastName)) .
                        '@' .
                        $this->faker->domainName(),

                    'password' => Hash::make('password'),
                    'role' => 2,
                ]);

                $media = Media::create([
                    'storage_path' => Media::DEFAULT_USER,
                ]);

                Profiles::create([
                    'name' => $firstName . ' ' . $lastName,
                    'position' => $this->faker->jobTitle(),
                    'company_id' => $companyId,
                    'user_id' => $user->id,
                    'media_id' => $media->id,
                ]);
            }
        });

        $mediaCompany = Media::create([
            'storage_path' => Media::DEFAULT_COMPANY,
        ]);

        $commpany = Companies::create([
            'name' => "Admin Company",
            'address' => "Jl. Administator",
            'phone' => $this->faker->e164PhoneNumber(),
            'email' => "admin@admin.com",
            'website' => $this->faker->domainName(),
            'media_id' => $mediaCompany->id,
        ]);

        $mediaUser = Media::create([
            'storage_path' => Media::DEFAULT_USER,
        ]);

        $admin = User::create([
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin@admin.com'),
            'role' => 3
        ]);

        Profiles::create([
            'name' => "Admin",
            'position' => "Super Administator",
            'company_id' => $commpany->id,
            'user_id' => $admin->id,
            'media_id' => $mediaUser->id,
        ]);
    }
}
