<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $path = 'public/assets/sql_files/geo_locations.sql';
        DB::unprepared(file_get_contents($path));

        $path = 'public/assets/sql_files/locations.sql';
        DB::unprepared(file_get_contents($path));
        $path = 'public/assets/sql_files/locations_second.sql';
        DB::unprepared(file_get_contents($path));
        $path = 'public/assets/sql_files/locations_third.sql';
        DB::unprepared(file_get_contents($path));
        $path = 'public/assets/sql_files/locations_four.sql';
        DB::unprepared(file_get_contents($path));
        $path = 'public/assets/sql_files/locations_five.sql';
        DB::unprepared(file_get_contents($path));
        $this->command->info('Location and Geo Location table seeded!');
    }
}
