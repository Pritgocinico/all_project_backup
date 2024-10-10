<?php

namespace App\Helpers;

use App\Models\Attendance;
use App\Models\BreakLog;
use Carbon\CarbonInterval;
use App\Models\ShiftTime;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Str;
use Auth;

class UtilityHelper
{
    public static function convertDmyAMPMFormat($date)
    {
        if ($date == null) {
            return Carbon::now()->format('d-m-Y h:i:s A');
        }
        return Carbon::parse($date)->format('d-m-Y h:i:s A');
    }
    public static function getDiffBetweenDateLeave($feature, $start, $end)
    {
        if ($start == null || $end == null) {
            return 0;
        }

        $date1 = Carbon::parse($start);
        $date2 = Carbon::parse($end);

        $diffInDays = $date1->diffInDays($date2) + 1;
        if ($feature == 0) {
            if ($diffInDays == 1) {
                return 0.5;
            } else {
                return $diffInDays / 2;
            }
        } elseif ($feature == 1) {
            return $diffInDays;
        }
        return 0;
    }
    public static function getHumanReadDiff($time)
    {
        if ($time == null) {
            return Carbon::now()->diffForHumans();
        }
        return Carbon::now()->diffForHumans($time);
    }

    public static function totalDayOfMonth($month, $year)
    {
        $now = Carbon::create($year, $month, 1);
        $totalDaysInMonth = $now->daysInMonth;
        $sundaysCount = 0;
        for ($day = 1; $day <= $totalDaysInMonth; $day++) {
            $date = Carbon::create($now->year, $now->month, $day);
            if ($date->isSunday()) {
                $sundaysCount++;
            }
        }
        return $totalDaysInMonth - $sundaysCount;
    }
    public static function getWeekOff($month, $year)
    {
        $startDate = Carbon::create($year, $month, 1);
        $endDate = $startDate->copy()->endOfMonth();
        $sundayCount = 0;
        while ($startDate->lte($endDate)) {
            if ($startDate->dayOfWeek === Carbon::SUNDAY) {
                $sundayCount++;
            }
            $startDate->addDay();
        }
        return $sundayCount;
    }
    public static function sumTime($diff_in_days)
    {
        $time = (array)$diff_in_days;
        $time = array_filter($time, function ($item) {
            return !in_array($item, ['00:00:00', '0:00:00']);
        });

        $begin = \Carbon\Carbon::createFromFormat('H:i:s', '00:00:00');
        $end = clone $begin;

        foreach ($time as $element) {
            $dateTime = \Carbon\Carbon::createFromFormat('H:i:s', $element);
            $end->addHours((int)$dateTime->format('H'))
                ->addMinutes((int)$dateTime->format('i'))
                ->addSeconds((int)$dateTime->format('s'));
        }

        return sprintf(
            '%02d:%02d:%02d',
            $end->diffInDays($begin) * 24 + $end->format('H'),
            $end->format('i'),
            $end->format('s')
        );
    }

    public static function convertYmd($date)
    {
        if ($date == null) {
            return Carbon::now()->format('Y-m-d');
        }
        return Carbon::parse($date)->format('Y-m-d');
    }

    public static function convertHIAFormat($time)
    {
        if ($time == null) {
            return Carbon::now()->format('h:i A');
        }
        return Carbon::parse($time)->format('h:i A');
    }
    public static function getPastMonths()
    {
        $currentMonth = Carbon::now()->month;
        $months = [];
        if ($currentMonth === 1) {
            $currentMonth = 12;
        }
        for ($i = 1; $i < $currentMonth; $i++) {
            $months[] = Carbon::create()->month($i)->format('F');
        }

        return $months;
    }
    public static function getPastMonthsWithCurrent()
    {
        $currentMonth = Carbon::now()->month;
        $months = [];
        if ($currentMonth === 1) {
            $currentMonth = 12;
        }
        for ($i = 1; $i <= $currentMonth; $i++) {
            $months[] = Carbon::create()->month($i)->format('F');
        }

        return $months;
    }

    public static function getCurrentMonth()
    {
        return Carbon::now()->month;
    }
    public static function getCurrentYear()
    {
        return Carbon::now()->year;
    }
    public static function getTotalOverTime($id)
    {
        $userDetail = User::find($id);
        $attendanceDetail = Attendance::where('user_id', $id)->whereDate('attendance_date', Carbon::now())->first();
        $start = Carbon::parse($attendanceDetail->login_time);
        $end = Carbon::parse($attendanceDetail->logout_time);

        $diffInHours = number_format($start->diffInHours($end));

        $shiftDetail = ShiftTime::where('id', $userDetail->shift_id)->first();
        $diffInHoursShift = 8;
        if (isset($shiftDetail)) {
            $startTimeShift = $shiftDetail->shift_start_time;
            $endTimeShift = $shiftDetail->shift_end_time;
            $startShift = Carbon::createFromFormat('H:i:s', $startTimeShift);
            $endShift = Carbon::createFromFormat('H:i:s', $endTimeShift);
            $diffInHoursShift = number_format($startShift->diffInHours($endShift));
        }

        $overtime = 0;
        if ($diffInHours > $diffInHoursShift) {
            $overtime = $diffInHours - $diffInHoursShift;
        }
        return $overtime;
    }

    public static function getUserTotalOverTime($id, $month, $year)
    {
        $totalOvertime = Attendance::where('user_id', $id)->whereMonth('attendance_date', $month)->whereYear('attendance_date', $year)->sum('over_time');
        return $totalOvertime;
    }

    public static function convertMonthNumberToMonthName($month)
    {
        return Carbon::createFromFormat('F', Str::title($month))->month;
    }

    public static function getInitials($name)
    {
        $names = explode(' ', $name);
        $initials = '';

        if (count($names) > 1) {
            $initials = strtoupper($names[0][0] . $names[1][0]);
        } else {
            $initials = strtoupper($names[0][0]);
        }

        return $initials;
    }
    public static function convertFJYFormat($date)
    {
        if ($date == null) {
            return Carbon::now()->format('F j, Y');
        }
        return Carbon::parse($date)->format('F j, Y');
    }

    public static function getHourBetweenTwoTimes($startTime, $endTime, $id)
    {
        if ($endTime == null) {
            return 0;
        }
        $breakDetail = BreakLog::whereDate('date', Carbon::now())->where('user_id', $id)->get();

        $diffSecond = 0;
        if ($breakDetail->isNotEmpty()) {
            foreach ($breakDetail as $break) {
                $diffSecond += Carbon::parse($break->break_start)->diffInSeconds(Carbon::parse($break->break_over));
            }
        }
        $startTime = Carbon::parse($startTime)->format('H:i:s');
        $totalSecondsWorked = Carbon::createFromTimeString($startTime)->diffInSeconds(Carbon::createFromTimeString($endTime));
        $breakTimeInHours = $diffSecond / 3600;
        $workedTimeInHours = $totalSecondsWorked / 3600;
        $netWorkingHours = $workedTimeInHours - $breakTimeInHours;
        return number_format(max($netWorkingHours, 0), 2);
    }
    public static function getHourBetweenTwoTimesAttendance($startTime, $endTime)
    {
        $start = Carbon::parse($startTime);
        $end = Carbon::parse($endTime);

        $totalSeconds  = $start->diffInSeconds($end);
        $id = Auth::user()->id;
        $breakDetail = BreakLog::whereDate('date', Carbon::now())->where('user_id', $id)->get();

        $diffSecond = 0;
        if ($breakDetail->isNotEmpty()) {
            foreach ($breakDetail as $break) {
                $diffSecond += Carbon::parse($break->break_start)->diffInSeconds(Carbon::parse($break->break_over));
            }
        }
        $netSeconds = max($totalSeconds - $diffSecond, 0); 
        $hours = floor($netSeconds / 3600);
        $minutes = floor(($netSeconds % 3600) / 60);
        $seconds = $netSeconds % 60;
        return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);;
    }

    public static function getDiffInSecond($start, $end)
    {
        return Carbon::parse($start)->diffInSeconds(Carbon::parse($end));
    }

    public static function formatSecondsToTime($seconds)
    {
        $interval = CarbonInterval::seconds($seconds);
        return $interval->cascade()->format('%H:%I:%S');
    }
    public static function convertDMYFormat($date)
    {
        if ($date == null) {
            return Carbon::now()->format('d-m-Y');
        }
        return Carbon::parse($date)->format('d-m-Y');
    }
    public static function getDiffInDays($start, $end)
    {
        return Carbon::parse($start)->diffInDays(Carbon::parse($end)) + 1;
    }
    public static function getCurrentMonthName()
    {
        return Carbon::now()->format('F');
    }
    public static function getWorkingDaysInMonth($monthName)
    {
        $year = Carbon::now()->year;

        // Create Carbon instances for the first and last day of the month
        $startOfMonth = Carbon::parse("first day of {$monthName} {$year}");
        $endOfMonth = Carbon::parse("last day of {$monthName} {$year}");

        $workingDays = 0;

        // Iterate through each day of the month
        while ($startOfMonth->lte($endOfMonth)) {
            // Check if the day is not Sunday
            if (!$startOfMonth->isSunday()) {
                $workingDays++;
            }
            $startOfMonth->addDay();
        }

        return $workingDays;
    }

    public static function getPastMonthsSalarySlip($salarySlips)
    {

        $currentMonth = Carbon::now()->month;
        $months = [];
        if ($currentMonth === 1) {
            $currentMonth = 12;
        }
        for ($i = 1; $i < $currentMonth; $i++) {
            $monthName = Carbon::create()->month($i)->format('F');
            if (!in_array($monthName, $salarySlips)) {
                $months[] = Carbon::create()->month($i)->format('F');
            }
        }
        return $months;
    }

    public static function convertFDYFormat($date)
    {
        if ($date == null) {
            return Carbon::now()->format('F d, Y');
        }
        return Carbon::parse($date)->format('F d, Y');
    }
    public static function getHIAFormatFromFullDate($date)
    {
        if ($date == null) {
            return Carbon::now()->format('h:i A');
        }
        return Carbon::parse($date)->format('h:i A');
    }

    public static function getDMYFormatFromFullDate($date)
    {
        if ($date == null) {
            return Carbon::now()->format('d-M-y');
        }
        return Carbon::parse($date)->format('d-M-y');
    }
    public static function getPreviousMonthName(){
        return Carbon::now()->subMonth()->format('F');
    }
    public static function calculateAge($date){
        if($date == null){
            return 0;
        }
        return Carbon::parse($date)->age;
    }
    public static function calculateHalfDay()
    {
        $shiftId = Auth::user()->shift_id;
        $shift = ShiftTime::where('id', $shiftId)->first();
        $time = $shift->shift_start_time;
        $loginTime = Carbon::now();
        $halfDayTime = Carbon::parse($time)->addMinutes(10);
        if ($loginTime->greaterThan($halfDayTime)) {
            return 2;
        }
        return 1;
    }
}
