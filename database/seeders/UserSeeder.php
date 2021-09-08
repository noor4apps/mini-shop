<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::insert([
            [
                'name' => 'Admin',
                'type' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('12345678'),
            ],
            [
                'name' => 'Seller',
                'type' => 'seller',
                'email' => 'seller@gmail.com',
                'password' => bcrypt('12345678'),
            ],
            [
                'name' => 'Customer',
                'type' => 'customer',
                'email' => 'customer@gmail.com',
                'password' => bcrypt('12345678'),
            ]
        ]);
    }
}
