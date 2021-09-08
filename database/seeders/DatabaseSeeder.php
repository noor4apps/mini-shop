<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Prophecy\Call\Call;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
//         \App\Models\User::factory(10)->create();

        File::deleteDirectory(public_path('uploads/images'));

        \App\Models\Product::factory(5)->create();

        $this->call(UserSeeder::class);

    }
}
