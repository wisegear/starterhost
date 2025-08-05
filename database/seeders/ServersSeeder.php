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
                'provider' => 'NameCrane',
                'location' => 'UK',
                'cpanelUrl' => 'https://uk-shared01.cpanelplatform.com:2083/ ',
                'whmUrl' => 'https://uk-shared01.cpanelplatform.com:2087/',
                'apiKey' => 'HZIAK7R7P68DM3LYUMZEW4DYPSBLMXCO',
                'username' => 'wiseuk25',
                'package' => 'wiseuk25_default',
                'ns1' => 'ns1.private-nameserver.net',
                'ns2' => 'ns2.private-nameserver.net',
                'ip' => '162.244.95.12',
            ]);
      
    }
}
