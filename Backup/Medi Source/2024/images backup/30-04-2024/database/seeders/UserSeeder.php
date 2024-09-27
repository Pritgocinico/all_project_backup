<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->updateOrInsert([
            'name'      => 'admin',
            'email'     => 'admin@admin.com',
            'phone'     => '1111111111',
            'password'  => Hash::make('admin'),
            'role'      => 1,
            'status'    => 1,
            'address'   => 'ahmedabad',
            'profile_image' => 'profile.jpg',
        ]);
    }
}
