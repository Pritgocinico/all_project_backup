<?php

namespace App\Http\Middleware;

use App\Helpers\UtilityHelper;
use App\Models\Attendance;
use App\Models\Log;
use App\Models\ShiftTime;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AutoLogout
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            $attendance = Attendance::where('user_id', $user->id)->whereDate('attendance_date', Carbon::today())->first();
            $shiftDetail = ShiftTime::where('id', $user->shift_id)->first();
            if (isset($attendance) && $attendance->logout_time == null && isset($shiftDetail)) {
                $logout = Carbon::now();
                $shiftEndTime = Carbon::parse($shiftDetail->end_time);
                if ($logout->greaterThan($shiftEndTime)) {
                    $totalWorkHour = UtilityHelper::getHourBetweenTwoTimes($attendance->login_time, $logout, $user->id);
                    $status = "0";
                    if ((int) $totalWorkHour < 8) {
                        $status = "2";
                    } else if ((int) $totalWorkHour > 8) {
                        $status = "1";
                    }
                    $attendance->logout_time = $logout;
                    $attendance->status = $status;
                    $attendance->save();

                    Log::create([
                        'user_id' => $user->id,
                        'module' => 'Logout',
                        'description' => $user->name . " Logged Out"
                    ]);
                    Auth::logout();
                    return redirect()->route('login')->with('status', 'You have been automatically logged out.');
                }
            }
            return $next($request);
        }
        return $next($request);
    }
}
