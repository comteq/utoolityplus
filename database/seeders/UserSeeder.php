<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create a regular user
        User::create([
            'name' => 'Regular User',
            'email' => 'user@user.com',
            'password' => bcrypt('password'), // You might want to use Hash::make() instead
            'role' => User::ROLE_USER,
        ]);

        // Create an admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'), // You might want to use Hash::make() instead
            'role' => User::ROLE_ADMIN,
        ]);
    }
}