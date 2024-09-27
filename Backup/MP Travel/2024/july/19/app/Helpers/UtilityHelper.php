<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\File;

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
    public static function totalDayOfMonth()
    {
        $now = Carbon::now();
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
}
