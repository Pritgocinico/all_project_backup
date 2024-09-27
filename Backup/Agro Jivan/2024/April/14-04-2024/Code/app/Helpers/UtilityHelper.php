<?php

namespace App\Helpers;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Pagination\LengthAwarePaginator;

class UtilityHelper
{
    public static function convertFullDateTime($date)
    {
        if ($date == null) {
            return Carbon::now()->format('j M Y, g:i A');
        }
        return Carbon::parse($date)->format('j M Y, g:i A');
    }
    public static function convertMDY($date)
    {
        if ($date == null) {
            return Carbon::now()->format('d-m-Y');
        }
        return Carbon::parse($date)->format('d-m-Y');
    }

    public static function convertYmd($date)
    {
        if ($date == null) {
            return Carbon::now()->format('Y-m-d');
        }
        return Carbon::parse($date)->format('Y-m-d');
    }

    public static function convertDmy($date)
    {
        if ($date == null) {
            return Carbon::now()->format('d/m/Y');
        }
        return Carbon::parse($date)->format('d/m/Y');
    }

    public static function convertDmyWith12HourFormat($date)
    {
        if ($date == null) {
            return Carbon::now()->format('d-m-Y h:i:s A');
        }
        return Carbon::parse($date)->format('d-m-Y h:i:s A');
    }

    public static function getDiffBetweenDates($start, $end)
    {
        if ($start == null || $end == null) {
            $now = Carbon::now();
            $end_date = Carbon::parse($end);
            return $end_date->diffInDays($now);
        }
        $end_date = Carbon::parse($end);
        return $end_date->diffInDays($start) + 1;
    }

    public static function getDiffBetweenTwoTime($sTime)
    {
        $startTime = Carbon::parse($sTime);
        $finishTime = Carbon::now();
        return $finishTime->diffInHours($startTime);
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
            $end->addHours($dateTime->format('H'))
                ->addMinutes($dateTime->format('i'))
                ->addSeconds($dateTime->format('s'));
        }
        return sprintf(
            '%s:%s:%s',
            $end->diffInDays($begin),
            $end->format('i'),
            $end->format('s')
        );
    }
    public static function convertDmyWithAMPMFormat($date)
    {
        if ($date == null) {
            return Carbon::now()->format('d-m-Y h:i A');
        }
        return Carbon::parse($date)->format('d-m-Y h:i A');
    }

    public static function getHumanReadDiff($time)
    {
        if ($time == null) {
            return Carbon::now()->diffForHumans();
        }
        return Carbon::now()->diffForHumans($time);
    }
    public static function getTotalSundaysOfMonth($monthNumber, $year)
    {
        $firstDayOfMonth = Carbon::create($year, $monthNumber, 1);
        $lastDayOfMonth = Carbon::create($year, $monthNumber, $firstDayOfMonth->daysInMonth);

        $totalSundays = $firstDayOfMonth->diffInDaysFiltered(function ($date) {
            return $date->dayOfWeek === Carbon::SUNDAY;
        }, $lastDayOfMonth);

        if ($firstDayOfMonth->dayOfWeek === Carbon::SUNDAY) {
            $totalSundays++;
        }
        return $totalSundays;
    }
    public static function getTotalMonthPast($startMonth)
    {
        $months = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];
        $startIndex = $startMonth - 1;
        $pastMonths = array_slice($months, $startIndex);
        return count($months) - count($pastMonths);
    }

    public static function monthName($number)
    {
        if ($number == null) {
            return false;
        }
        return Carbon::create()->month($number)->format('F');
    }

    public static function getCurrentFinancialYear()
    {
        $currentDate = Carbon::now();
        $currentYear = $currentDate->year;
        if ($currentDate->month >= 4) {
            return $currentYear . '-' . ($currentYear + 1);
        }
        return ($currentYear - 1) . '-' . $currentYear;
    }

    public static function getAllCurrentYearDate($page,$admin = "")
    {
        $currentYear = Carbon::now()->year;
        $todayDate = Carbon::now()->startOfDay();

        // Retrieve all previous dates of the current year
        $previousDates[] = $todayDate;
        for ($i = 1; $i <= 12; $i++) {
            for ($j = 1; $j <= 31; $j++) {
                $date = Carbon::create($currentYear, $i, $j)->startOfDay();
                if ($date < $todayDate) {
                    $previousDates[] = $date;
                } else {
                    break;
                }
            }
        }
        usort($previousDates, function ($a, $b) use ($todayDate) {
            if ($a->equalTo($todayDate)) {
                return -1; // $a is today's date, should come first
            } elseif ($b->equalTo($todayDate)) {
                return 1; // $b is today's date, should come first
            } else {
                return $a->gt($b) ? -1 : 1; // Sort in descending order
            }
        });
    
        // Paginate the array
        $perPage = 15;
        if($admin == "yes"){
            $perPage = 20;
        }
        if($page == null){
            $page = LengthAwarePaginator::resolveCurrentPage('page');
        }
        $items = array_slice($previousDates, ($page - 1) * $perPage, $perPage);
        $paginator = new LengthAwarePaginator($items, count($previousDates), $perPage, $page, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
            
        ]);
        $paginator->setCollection($paginator->getCollection()->map(function ($date) {
            return $date->format('d-m-Y');
        }));
        return $paginator;
    }

    public static function convertMDYAMPM(){
        return Carbon::now()->format('m/d/Y h:i A');
    }
}
