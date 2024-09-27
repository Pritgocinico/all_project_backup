<?php

namespace App\Helpers;

use Carbon\Carbon;

class UtilityHelper
{
    public static function convertDmyAMPMFormat($date)
    {
        if ($date == null) {
            return Carbon::now()->format('d-m-Y h:i:s A');
        }
        return Carbon::parse($date)->format('d-m-Y h:i:s A');
    }
}