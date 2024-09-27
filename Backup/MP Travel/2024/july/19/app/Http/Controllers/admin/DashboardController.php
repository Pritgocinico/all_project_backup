<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Role;
use App\Models\User;

class DashboardController extends Controller
{
    public function index(){
        $data['userCount'] = User::where('role_id', 2)->count();
        $data['roleCount'] = Role::count();
        $data['departmentCount'] = Department::count();
        $data['designationCount'] = Designation::count();
        return view('admin.dashboard', $data);
    }
    
}
