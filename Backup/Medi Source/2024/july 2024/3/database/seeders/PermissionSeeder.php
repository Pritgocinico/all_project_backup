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
                'permission_name' => 'Payment Detail List',
            ],
            [
                'permission_name' => 'Order List/View',
            ],
            [
                'permission_name' => 'Order Add/Edit/Delete',
            ],
            [
                'permission_name' => 'Order Print Label',
            ],
            [
                'permission_name' => 'Order Package Slip',
            ],
            [
                'permission_name' => 'Order Generate Invoice',
            ],
            [
                'permission_name' => 'Product Add/Edit/Delete',
            ],
            [
                'permission_name' => 'Product List/View',
            ],
            [
                'permission_name' => 'Category Add/Edit/Delete',
            ],
            [
                'permission_name' => 'Category List/View',
            ],
            [
                'permission_name' => 'Dosage Form Add/Edit/Delete',
            ],
            [
                'permission_name' => 'Dosage Form List/View',
            ],
            [
                'permission_name' => 'Resource Add/Edit/Delete',
            ],
            [
                'permission_name' => 'Resource List/View',
            ],
            [
                'permission_name' => 'Doctor Add/Edit/Delete',
            ],
            [
                'permission_name' => 'Doctor List/View',
            ],
            [
                'permission_name' => 'Doctor Status Update',
            ],
            [
                'permission_name' => 'Inquiry List/View',
            ],
            [
                'permission_name' => 'Inquiry Add/Edit/Delete',
            ],
            [
                'permission_name' => 'Help List/View',
            ],
            [
                'permission_name' => 'Help Add/Edit/Delete',
            ],
            [
                'permission_name' => 'Lots List/View',
            ],
            [
                'permission_name' => 'Lots Add/Edit/Delete',
            ],
            [
                'permission_name' => 'Employee List/View',
            ],
            [
                'permission_name' => 'Employee Add/Edit/Delete',
            ],
            [
                'permission_name' => 'Coupon List/View',
            ],
            [
                'permission_name' => 'Coupon Add/Edit/Delete',
            ],
            [
                'permission_name' => 'Password View',
            ],
            [
                'permission_name' => 'Doctor Card Information',
            ],
        ];
        foreach ($permissions as $key => $per) {
            $permissionData = count(Permission::where('permission_name', $per['permission_name'])->get());
            if ($permissionData == 0) {
                Permission::create(['permission_name' => $per['permission_name']]);
            }
        }
    }
}
