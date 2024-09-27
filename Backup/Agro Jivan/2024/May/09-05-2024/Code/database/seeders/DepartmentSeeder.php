<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $getAllDepartment = Department::get()->toArray();
        if(!in_array('HR Department', array_column($getAllDepartment, 'department_name'))){
            Department::create([
                'department_name' => 'HR Department',
            ]);
        }
        if(!in_array('Sales', array_column($getAllDepartment, 'department_name'))){
            Department::create([
                'department_name' => 'Sales',
            ]);
        }
        if(!in_array('Confirmation Department', array_column($getAllDepartment, 'department_name'))){
            Department::create([
                'department_name' => 'Confirmation Department',
            ]);
        }
        if(!in_array('Transport Department', array_column($getAllDepartment, 'department_name'))){
            Department::create([
                'department_name' => 'Transport Department',
            ]);
        }
        if(!in_array('Warehouse Department', array_column($getAllDepartment, 'department_name'))){
            Department::create([
                'department_name' => 'Warehouse Department',
            ]);
        }
        if(!in_array('Doctor Social', array_column($getAllDepartment, 'department_name'))){
            Department::create([
                'department_name' => 'Doctor Social',
            ]);
        }
        if(!in_array('After Sale Service', array_column($getAllDepartment, 'department_name'))){
            Department::create([
                'department_name' => 'After Sale Service',
            ]);
        }
        if(!in_array('System Engineer', array_column($getAllDepartment, 'department_name'))){
            Department::create([
                'department_name' => 'System Engineer',
            ]);
        }
        if(!in_array('Account', array_column($getAllDepartment, 'department_name'))){
            Department::create([
                'department_name' => 'Account',
            ]);
        }
    }
}
