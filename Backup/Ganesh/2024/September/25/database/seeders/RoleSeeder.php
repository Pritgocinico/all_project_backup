<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $getAllRole = Role::get()->toArray();
        $name = "name";
        if(!in_array('admin', array_column($getAllRole, $name))){
            Role::create([
                $name => 'admin',
            ]);
        }
        if(!in_array('user', array_column($getAllRole, $name))){
            Role::create([
                $name => 'user',
            ]);
        }
        if(!in_array('measurement', array_column($getAllRole, $name))){
            Role::create([
                $name => 'measurement',
            ]);
        }
        if(!in_array('quatation', array_column($getAllRole, $name))){
            Role::create([
                $name => 'quatation',
            ]);
        }
        if(!in_array('workshop', array_column($getAllRole, $name))){
            Role::create([
                $name => 'workshop',
            ]);
        }
        if(!in_array('fitting', array_column($getAllRole, $name))){
            Role::create([
                $name => 'fitting',
            ]);
        }
        if(!in_array('business', array_column($getAllRole, $name))){
            Role::create([
                $name => 'business',
            ]);
        }
        if(!in_array('purchase', array_column($getAllRole, $name))){
            Role::create([
                $name => 'purchase',
            ]);
        }
        if(!in_array('customer', array_column($getAllRole, $name))){
            Role::create([
                $name => 'customer',
            ]);
        }
        if(!in_array('Quality Analytic', array_column($getAllRole, $name))){
            Role::create([
                $name => 'Quality Analytic',
            ]);
        }
    }
}
