<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DeviceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Insert sample data into the 'device' table
        DB::table('device')->insert([
            'Device_Name' => 'Utoolityplus',
            'State' => 'Active',
            'Device_IP' => '192.168.1.100',
            'Pin_Number' => 8,
        ]);
    }
}
