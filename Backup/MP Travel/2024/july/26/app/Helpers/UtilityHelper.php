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

}
