<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

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
