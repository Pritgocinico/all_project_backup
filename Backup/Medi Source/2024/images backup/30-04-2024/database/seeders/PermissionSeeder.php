<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\UserPermission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            [
                'permission_name' => 'Card List',
            ],
            [
                'permission_name' => 'Order List',
            ],
            [
                'permission_name' => 'Checkout Page',
            ],
            [
                'permission_name' => 'add To Cart',
            ],
            [
                'permission_name' => 'Product edit',
            ],
            [
                'permission_name' => 'Product add',
            ],
            [
                'permission_name' => 'Product delete',
            ],
            [
                'permission_name' => 'product-show',
            ],
        ];
        $roleList = Role::where('id', '!=', 1)->get();
        foreach ($roleList as $key => $role) {
            foreach ($permissions as $key => $per) {
                $permissionData = Permission::where('permission_name', $per['permission_name'])->first();
                if ($permissionData !== null) {
                    $userPermission = UserPermission::where('role_id', $role->id)->where('permission_id', $permissionData->id)->first();
                    if (!isset($userPermission)) {
                            $data['role_id'] = $role->id;
                            $data['permission_id'] = $permissionData->id;
                            $data['status'] = '1';
                            UserPermission::create($data);
                    }
                } else {
                    $insert = Permission::create(['permission_name' => $per['permission_name']]);
                    if ($insert) {
                        $data['role_id'] = $role->id;
                        $data['permission_id'] = $insert->id;
                        $data['status'] = '1';
                        UserPermission::create($data);
                    }
                }
            }
        }
    }
}
