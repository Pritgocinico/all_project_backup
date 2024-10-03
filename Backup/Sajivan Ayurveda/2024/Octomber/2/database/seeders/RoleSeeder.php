<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roleName = ['admin','employee','user'];
        foreach($roleName as $role) {
            Role::updateOrCreate(
                ['name'=>$role],
                ['name'=>$role]
            );
        }
    }
}
