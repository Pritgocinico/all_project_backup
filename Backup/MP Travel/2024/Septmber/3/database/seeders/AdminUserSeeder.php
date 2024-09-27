<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('email', 'admin@admin.com')->first();
        if (empty($admin)) {
            User::insert([
                'name'      => 'Super Admin',
                'email'     => 'admin@admin.com',
                'phone_number'     => '1111111111',
                'password'  => Hash::make('admin'),
                'role_id'  => 1,
            ]);
        }
    }
}
