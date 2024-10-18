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
        $menus = ['dashboard', 'department', 'designation', 'role', 'user', 'info_sheet', 'leave', 'holiday', 'ticket', 'attendance', 'daily attendance', 'certificate', 'pay salary', 'lead', 'Push Lead', 'follow_up', 'Service Preference', 'customer', 'product', 'category', 'disease', 'log'];
        foreach ($menus as $key => $menu) {
            $menuDetail = Menu::updateOrCreate(
                ['name' => $menu],
                [
                    'name' => $menu,
                    'order' => $key,
                    'parent_id' => 0,
                ]
            );

            Access::updateOrCreate([
                'menu_id' => $menuDetail->id,
                'role_id' => 1,
            ], [
                'menu_id' => $menuDetail->id,
                'role_id' => 1,
                'status' => 2,
            ]);

            $roleList = Role::where('id', '!=', '1')->get();
            foreach ($roleList as $key => $role) {
                $accessExist = Access::where('menu_id',$menuDetail->id)->where('role_id',$role->id)->first();
                if(!$accessExist){
                    $access = new Access();
                    $access->menu_id = $menuDetail->id;
                    $access->role_id = $role->id;
                    $access->status = 0;
                    $access->save();
                }
            }
        }
    }
}
