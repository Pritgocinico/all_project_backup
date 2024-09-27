<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name'      => 'admin',
            'email'     => 'admin@admin.com',
            'phone'     => '1111111111',
            'password'  => Hash::make('admin'),
            'role'      => 1,
            'status'    => 1
        ]);
    }
}
