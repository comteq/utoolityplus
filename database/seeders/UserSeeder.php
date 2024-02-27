<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\unit;
use App\Models\device;
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
        // Create an admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@admin.com',
            'password' => Hash::make('1Qaz2wsx'),
            'role' => User::ROLE_ADMIN,
        ]);

        device::create([
            'Device_name' => 'utoolityplus',
            'State' => 'Active',
            'Device_IP' => '192.168.1.110',
            'Pin_Number' => '8',
        ]);

        $data = [
            [
                'id' => 1,
                'Pin_Num' => 1,
                'Pin_Name' => 'AC',
                'Status' => '0'
            ],
            [
                'id' => 2,
                'Pin_Num' => 2,
                'Pin_Name' => 'Lights',
                'Status' => '0'
            ]
        ];

        unit::insert($data);
        
    }
}