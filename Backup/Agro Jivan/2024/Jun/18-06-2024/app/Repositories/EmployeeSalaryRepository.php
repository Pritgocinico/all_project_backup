<?php 

namespace App\Repositories;

use App\Interfaces\EmployeeSalaryRepositoryInterface;
use App\Models\Attendance;
use App\Models\User;


class EmployeeSalaryRepository implements EmployeeSalaryRepositoryInterface
{
    public function store($data){
        return Attendance::create($data);
    }

    public function getAllData(){
        return Attendance::where('user_id', auth()->user()->id)->where('status', 1)->where('deleted_at', NULL)->latest()->paginate(15);
    }

    public function getUserSalaryData(){
        $salary = auth()->user()->employee_salary;
        $basic = $salary * (70/100);
        $hra = $salary * (20/100);
        $other_allowance = $salary * (10/100);
        return response()->json(array('salary' => $salary, 'basic' => $basic, 'hra' => $hra, 'other_allowance' => $other_allowance));
    }

    public function getUserData(){
        return User::where('id', auth()->user()->id)->where('deleted_at', NULL)->first();
    }
    
    public function getDetailById($id){
        return Attendance::where('id',$id)->first();
    }

    public function update($data,$where){
        return Attendance::where($where)->update($data);
    }

    public function delete($id){
        return Attendance::where('id',$id)->delete();
    }
}