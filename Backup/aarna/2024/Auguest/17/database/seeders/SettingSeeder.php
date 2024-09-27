<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('settings')->insert([
            'logo'                  => 'logo.svg',
            'favicon'               => 'favicon.png',
            'site_name'             => env('APP_NAME'),
            'site_meta'             => '',
            'site_description'      => '',
            'site_url'              => env('APP_URL'),
            'date_format'           => 'F d, Y',
            'admin_email'           => '',
            'admin_password'        => '',
        ]);
    }
}
