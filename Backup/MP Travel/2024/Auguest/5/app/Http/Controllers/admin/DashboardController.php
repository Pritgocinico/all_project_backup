<?php

namespace App\Http\Controllers\admin;
use App\Helpers\UtilityHelper;
use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Role;
use App\Models\User;
use App\Models\Attendance;
use App\Models\BreakLog;
use App\Models\Lead;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(){
        $data['userCount'] = User::where('role_id', 2)->count();
        $data['roleCount'] = Role::count();
        $data['departmentCount'] = Department::count();
        $data['designationCount'] = Designation::count();
        $data['leadCount'] = Lead::count();
        if(Auth()->user()->role_id == 1){
            return view('admin.dashboard', $data);
        }
        return view('employee.dashboard');
    }

    public function break(){
        $today = Carbon::today()->toDateString();
        $attendance = Attendance::where('user_id',Auth()->user()->id)->whereDate('attendance_date', $today)->first();
        if (!blank($attendance)) {
            $data = [
                'attendance_id' => $attendance->id,
                'user_id' => Auth()->user()->id,
                'date' => date('Y-m-d'),
                'break_start' => Carbon::now(),
            ];
            BreakLog::create($data);
            
            $attendance->update(['break' => Carbon::now()]);
        }
        return redirect()->back();
    }

    public function sumTime()
    {
        $diff_in_days = [];
        $breaks = BreakLog::where('user_id', Auth()->user()->id)
            ->orderBy('id', 'DESC')
            ->whereDate('created_at', Carbon::today())
            ->get();

        if (!$breaks->isEmpty()) {
            foreach ($breaks as $br) {
                if ($br->break_complete) {
                    $to = new \Carbon\Carbon($br->break_start);
                    $from = new \Carbon\Carbon($br->break_complete);
                    $diff_in_days[] = $to->diff($from)->format('%H:%I:%S');
                }
            }
        }

        $time = array_filter($diff_in_days, function ($item) {
            return $item !== '00:00:00' && $item !== '0:00:00';
        });

        $begin = \Carbon\Carbon::createFromFormat('H:i:s', '00:00:00');
        $end = clone $begin;

        foreach ($time as $element) {
            try {
                $dateTime = \Carbon\Carbon::createFromFormat('H:i:s', $element);
                $end->addHours((int)$dateTime->format('H'))
                    ->addMinutes((int)$dateTime->format('i'))
                    ->addSeconds((int)$dateTime->format('s'));
            } catch (\Exception $e) {
                // Handle the exception if needed
            }
        }

        return sprintf(
            '%sh:%sm:%ss',
            $end->diffInHours($begin),
            $end->format('i'),
            $end->format('s')
        );
    }


    public function completeBreak()
    {
        $break_log = BreakLog::where('user_id',Auth()->user()->id)->orderBy('id','DESC')->first();
        if (!empty($break_log)) {
            $break_log->break_over = Carbon::now();
            $break_log->save();
        }
        return redirect()->back();
    }
}
