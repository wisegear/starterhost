<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       // For testing

            DB::table('servers')->insert([
                'provider' => '',
                'location' => '',
                'cpanelUrl' => '',
                'whmUrl' => '',
                'apiKey' => '',
                'username' => '',
                'package' => '',
                'ns1' => '',
                'ns2' => '',
                'ip' => '',
            ]);
      
    }
}
