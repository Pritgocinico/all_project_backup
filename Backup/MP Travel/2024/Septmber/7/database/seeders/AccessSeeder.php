<?php

namespace Database\Seeders;

use App\Models\Access;
use App\Models\Menu;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus = Menu::all();
        $roleList = Role::all();
        foreach ($menus as $key => $menu) {
            foreach ($roleList as $key => $role) {
                $status = 1;
                if($role->id == 1){
                    $status = 2;
                }
                Access::updateOrCreate(
                    ['menu_id' =>$menu->id,'role_id'=>$role->id],
                    ['menu_id' =>$menu->id,'role_id'=>$role->id,'status'=>$status],
                );
            }
        }
    }
}
