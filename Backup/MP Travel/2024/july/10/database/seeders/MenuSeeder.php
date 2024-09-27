<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Access;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus = ['dashboard', 'department', 'designation', 'role', 'user', 'log'];

        foreach ($menus as $menu) {
            $menuDetail = Menu::updateOrCreate(
                ['name' => $menu],
                ['name' => $menu]
            );

                Access::updateOrCreate([
                    'menu_id' => $menuDetail->id,
                    'role_id' => 1,
                ], [
                    'menu_id' => $menuDetail->id,
                    'role_id' => 1,
                    'status' => 2,
                ]);
            
            $roleList = Role::where('name', '!=', 'admin')->get();
            foreach ($roleList as $key => $role) {
                Access::updateOrCreate([
                    'menu_id' => $menuDetail->id,
                    'role_id' => $role->id,
                ], [
                    'menu_id' => $menuDetail->id,
                    'role_id' => $role->id,
                ]);
            }
        }
    }
}
