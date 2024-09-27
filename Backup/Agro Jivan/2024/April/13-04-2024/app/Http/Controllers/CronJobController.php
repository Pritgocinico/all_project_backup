<?php

namespace App\Http\Controllers;

use App\Interfaces\AttendanceRepositoryInterface;
use App\Interfaces\EmployeeRepositoryInterface;
use App\Interfaces\HolidayRepositoryInterface;
use App\Interfaces\LeaveRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CronJobController extends Controller
{
    protected $employeeRepository,$holidayRepository,$leaveRepository,$attendanceRepository = "";
    public function __construct(EmployeeRepositoryInterface $employeeRepository ,HolidayRepositoryInterface $holidayRepository, LeaveRepositoryInterface $leaveRepository, AttendanceRepositoryInterface $attendanceRepository)
    {
        $this->employeeRepository = $employeeRepository;
        $this->holidayRepository = $holidayRepository;
        $this->leaveRepository = $leaveRepository;
        $this->attendanceRepository = $attendanceRepository;
    }

    public function getFirstShiftUserAttendance(){
        $holiday = $this->holidayRepository->getTodayHoliday();
        if(blank($holiday)){
            $employeeList = $this->employeeRepository->getShiftWiseEmployee(1);
            foreach ($employeeList as $key => $employee) {
                $userLeaveCount = $this->leaveRepository->getTodayUserLeave($employee->id);
                if($userLeaveCount == 0){
                    $todayAttendance = $this->attendanceRepository->getTodayAttendanceUserByID($employee->id);
                    if($todayAttendance->login_time == ""){
                        $data['status'] = '0';
                    }
                    $where['user_id'] = $employee->id;
                    $this->attendanceRepository->updateAttendanceDetail($data,$where);
                }
            }
        }
        Log::info("Set The Attendance Status Cron Job Run.");
        return 1;
    }

    public function getSecondShiftUserAttendance(){
        $holiday = $this->holidayRepository->getTodayHoliday();
        if(blank($holiday)){
            $employeeList = $this->employeeRepository->getShiftWiseEmployee(2);
            foreach ($employeeList as $key => $employee) {
                $userLeaveCount = $this->leaveRepository->getTodayUserLeave($employee->id);
                if($userLeaveCount == 0){
                    $todayAttendance = $this->attendanceRepository->getTodayAttendanceUserByID($employee->id);
                    if($todayAttendance->login_time == ""){
                        $data['status'] = '0';
                    }
                    $where['user_id'] = $employee->id;
                    $this->attendanceRepository->updateAttendanceDetail($data,$where);
                }
            }
        }
        Log::info("Set The Attendance Status Cron Job Run.");
        return 1;
    }
    public function getThirdShiftUserAttendance(){
        $holiday = $this->holidayRepository->getTodayHoliday();
        if(blank($holiday)){
            $employeeList = $this->employeeRepository->getShiftWiseEmployee(3);
            foreach ($employeeList as $key => $employee) {
                $userLeaveCount = $this->leaveRepository->getTodayUserLeave($employee->id);
                if($userLeaveCount == 0){
                    $todayAttendance = $this->attendanceRepository->getTodayAttendanceUserByID($employee->id);
                    if($todayAttendance->login_time == ""){
                        $data['status'] = '0';
                    }
                    $where['user_id'] = $employee->id;
                    $this->attendanceRepository->updateAttendanceDetail($data,$where);
                }
            }
        }
        Log::info("Set The Attendance Status Cron Job Run.");
        return 1;
    }
}
