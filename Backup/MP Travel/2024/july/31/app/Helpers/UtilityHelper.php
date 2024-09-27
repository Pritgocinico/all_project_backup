<?php

namespace App\Helpers;

use App\Models\Attendance;
use App\Models\ShiftTime;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;

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

    public static function getImageUrl($image)
    {
        $imageUrl = asset('assets/img/user/user.jpg');
        if ($image !== null) {
            $imageUrl = asset('storage/' . $image);
        }
        return $imageUrl;
    }
    public static function totalDayOfMonth($month,$year)
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
    public static function getWeekOff($month,$year){
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

    public static function getUserTotalOverTime($id,$month,$year){
        $totalOvertime = Attendance::where('user_id', $id)->whereMonth('attendance_date', $month)->whereYear('attendance_date', $year)->sum('over_time');
        return $totalOvertime;
    }

    public static function convertMonthNumberToMonthName($month){
        return Carbon::createFromFormat('F', Str::title($month))->month;
    }
}
