<?php

namespace App\Http\Middleware;

use App\Helpers\UserLogHelper;
use App\Helpers\UtilityHelper;
use App\Models\Attendance;
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
            $Attendance = Attendance::where('user_id', $user->id)->whereDate('attendance_date', UtilityHelper::convertYmd(''))->first();
            if (isset($Attendance) && $Attendance->logout_time == null) {
                if ($user->shift_type == 1) {
                    $shiftEndTime = Carbon::createFromFormat('H:i', '17:30');
                }
                if ($user->shift_type == 2) {
                    $shiftEndTime = Carbon::createFromFormat('H:i', '18:30');
                }

                $now = Carbon::now();
                if ($now->gte($shiftEndTime)) {
                    $data = [
                        'logout_time' => Carbon::now(),
                    ];
                    $where = ['user_id' => Auth()->user()->id, 'attendance_date' => Carbon::now()->format('Y-m-d')];
                    Attendance::where($where)->update($data);
                    UserLogHelper::storeLog("Logout", ucfirst(Auth()->user()->name) . " Logout in Successfully");
                    Auth::logout();
                    return redirect()->route('login')->with('status', 'You have been automatically logged out.');
                }
            }
            return $next($request);
        }
        return $next($request);
    }
}
