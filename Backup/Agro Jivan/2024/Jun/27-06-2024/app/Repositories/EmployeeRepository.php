<?php

namespace App\Repositories;

use App\Interfaces\EmployeeRepositoryInterface;
use App\Models\SubLocation;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class EmployeeRepository implements EmployeeRepositoryInterface
{
    public function store($data)
    {
        return User::create($data);
    }

    public function getAllUsersIgnored($id)
    {
        return User::where('id', '!=', $id)->latest()->get();
    }

    public function getAllData($status, $role, $search, $type)
    {
        $query = $this->getQuery($status, $role, $search)->latest();
        if ($type == 'paginate') {
            $query = $query->paginate(15);
        }
        if ($type == "export") {
            $query = $query->get();
        }
        return $query;
    }
    public function getQuery($status, $role, $search)
    {
        return User::with('roleDetail')
            ->where('role_id', '!=', 1)
            ->when($status != "", function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($role != "", function ($query) use ($role) {
                $query->where('role_id', $role);
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%')
                        ->orWhere('phone_number', 'like', '%' . $search . '%')
                        ->orWhereHas('roleDetail',function($query)use($search){
                            $query->where('name','like','%'.$search.'%');
                        });
                });
            });
    }
    public function getDetailById($id)
    {
        return User::with('departmentDetail', 'departmentDetail.departmentNameDetail')->where('id', $id)->first();
    }

    public function update($data, $where)
    {
        return User::where($where)->update($data);
    }

    public function delete($id)
    {
        return User::where('id', $id)->delete();
    }

    public function getUserByMobileOrPhone($name)
    {
        return User::where('phone_number', $name)->orWhere('email', $name)->first();
    }
    public function getAllEmployee()
    {
        return User::where('role_id', 2)->get();
    }

    public function getUserListWithAbsentPresentDetail($from, $to,$manager ="")
    {
        $results = User::withCount([
            'attendancesDetail as absent_count' => function ($query)use($from,$to) {
                $query->where('status', '0')->whereDate('attendance_date', '>=', date('Y-m-d', strtotime($from)))
                ->whereDate('attendance_date', '<=', date('Y-m-d', strtotime($to)));;
            },
            'attendancesDetail as present_count' => function ($query)use($from,$to) {
                $query->whereIn('status', ['1', '2'])->whereDate('attendance_date', '>=', date('Y-m-d', strtotime($from)))
                ->whereDate('attendance_date', '<=', date('Y-m-d', strtotime($to)));;
            },
            'attendancesDetail as total_attendance'
        ])->where('role_id','!=','1')
        ->paginate(15);
        return $results;
    }

    public function getVillages()
    {
        return SubLocation::select('village_code', 'village_name')->get()->keyBy('village_name');
    }

    public function employeeCount()
    {
        return User::count();
    }

    public function getLastInsertId()
    {
        return User::all()->last()->id;
    }
    public function getNewSystemcode()
    {
        return 'AGRJVN-' . sprintf('%04d', User::all()->last()->id + 1);
    }

    public function getAllHR()
    {
        return User::where('role_id', '3')->get();
    }

    public function getOtherHrDetail()
    {
        return User::where('id', '!=', Auth()->user()->id)->where('role_id', '3')->get();
    }

    public function getShiftWiseEmployee($shift)
    {
        return User::where('role_id', 2)->where('shift_type', $shift)->latest()->get();
    }

    public function getAllSystemEngineer()
    {
        return User::where('role_id', '6')->get();
    }

    public function getAllDriver()
    {
        return User::where('role_id', '5')->get();
    }

    public function getAllEmployeeSearch($search, $status)
    {
        return User::with('roleDetail')
            ->where('role_id', 2)
            ->when($status != "", function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%')
                        ->orWhere('phone_number', 'like', '%' . $search . '%');
                });
            })->latest()->paginate(15);
    }

    public function getEmployeeCount()
    {
        return User::where('role_id', '2')->count();
    }

    public function getAllManagerList()
    {
        return User::where('role_id', '!=', 1)->where('is_manager', 1)->get();
    }

    public function getAllEmployeeTeam()
    {
        return User::with('teamDetail')->whereDoesntHave('teamDetail')->where('role_id', 2)->get();
    }

    public function getNotIncludeTeamEmployee($id)
    {
        return User::where('role_id', 2)
            ->whereDoesntHave('teamDetails', function ($query) use ($id) {
                $query->where('team_id', $id);
            })->get();
    }

    public function getTopFiveConfirmOrder($search,$date){
        return User::withCount(['confirmOrder'=>function($query)use($date){
            $query->when($date,function($query)use($date){
                $date1 = explode('/',$date);
                $query->whereDate('confirm_date','>=',$date1[0])->whereDate('confirm_date','<=',$date1[1]);
            });
        }])->when($search,function($query)use($search){
            $query->where('name','like','%'.$search.'%')
            ->orWhere('email','like','%'.$search.'%');
        })->where('role_id',2)->orderByDesc('confirm_order_count')
        ->limit(5)
        ->get();
    }

    public function getAllDriverSearch($search){
        return User::where('role_id', 5)
        ->withCount('onDeliverBatch','deliveredBatch','confirmOrder','returnOrder')
        ->when($search, function ($query) use ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('phone_number', 'like', '%' . $search . '%');
            });
        })->latest()->paginate(15);
    }

    public function getAllUser(){
        return User::get();
    }

    public function getEmployeeDetail($search,$userId,$date,$type){
        $query =  User::with('roleDetail')
            ->where('role_id', '!=', 1)
            ->when($userId, function ($query) use ($userId) {
                $query->where('id', $userId);
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%')
                        ->orWhere('system_code', 'like', '%' . $search . '%')
                        ->orWhereHas('roleDetail',function($query)use($search){
                            $query->where('name','like','%'.$search.'%');
                        });
                });
            })->When($date,function($query)use($date){
                $query->where(function($query)use($date){
                    $date1 = explode('/',$date);
                    $query->where('created_at',">=",$date1[0])->where('created_at',"<=",$date1[1]);
                });
            })->latest();
        if($type == "paginate"){
            $query = $query->paginate(15);
        }
        if($type == "export"){
            $query = $query->get();
        }
        return $query;
    }
}
