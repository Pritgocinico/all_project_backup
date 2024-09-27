<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;


class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $getAllRole = Role::get()->toArray();
        if(!in_array('admin', array_column($getAllRole, 'name'))){
            Role::create([
                'name' => 'admin',
            ]);
        }
        if(!in_array('employee', array_column($getAllRole, 'name'))){
            Role::create([
                'name' => 'employee',
            ]);
        }
        if(!in_array('Human Resource', array_column($getAllRole, 'name'))){
            Role::create([
                'name' => 'Human Resource',
            ]);
        }
        if(!in_array('Confirmation Department', array_column($getAllRole, 'name'))){
            Role::create([
                'name' => 'Confirmation Department',
            ]);
        }
        if(!in_array('Driver', array_column($getAllRole, 'name'))){
            Role::create([
                'name' => 'Driver',
            ]);
        }
        if(!in_array('System Engineer', array_column($getAllRole, 'name'))){
            Role::create([
                'name' => 'System Engineer',
            ]);
        }
        if(!in_array('Transport Department', array_column($getAllRole, 'name'))){
            Role::create([
                'name' => 'Transport Department',
            ]);
        }
        if(!in_array('Warehouse Manager', array_column($getAllRole, 'name'))){
            Role::create([
                'name' => 'Warehouse Manager',
            ]);
        }
        if(!in_array('Sales Manager', array_column($getAllRole, 'name'))){
            Role::create([
                'name' => 'Sales Manager',
            ]);
        }
        if(!in_array('After Sales Service', array_column($getAllRole, 'name'))){
            Role::create([
                'name' => 'After Sales Service',
            ]);
        }
    }
}
