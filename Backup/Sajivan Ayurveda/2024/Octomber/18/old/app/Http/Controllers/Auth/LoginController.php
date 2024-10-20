<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\UtilityHelper;
use App\Http\Controllers\Controller;
use App\Models\Log;
use App\Models\Attendance;
use App\Models\ShiftTime;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->only('logout');
        $this->middleware('guest')->except('logout');
        $page = "Login";
        view()->share('page', $page);
    }

    protected function loginPage()
    {
        if (Auth::check()) {
            return view('admin.dashboard');
        }
        return view('auth.login');
    }
    protected function authenticated(Request $request, $user)
    {
        Log::create([
            'user_id' => $user->id,
            'module' => 'Login',
            'description' => $user->name . " Logged In"
        ]);
        $id = Auth()->user()->role_id;

        if ($id != 1) {
            $where = ['user_id' => Auth()->user()->id, 'attendance_date' => Carbon::today()];
            $attendance = Attendance::where($where)->first();
            if ($attendance == null) {
                $attendance = new Attendance();
                $attendance->user_id = Auth()->user()->id;
                $attendance->attendance_date = Carbon::today();
                $attendance->login_time = Carbon::now();
                $attendance->status = UtilityHelper::calculateHalfDay();
                $attendance->save();
            }
        }
        if (Auth()->user()->role_id == 2) {
            return redirect()->route('emp-dashboard')->with('success', 'You have successfully logged in!');
        }
        if (Auth()->user()->role_id == 4) {
            return redirect()->route('hr-dashboard')->with('success', 'You have successfully logged in!');
        }
        return redirect()->intended($this->redirectPath())->with('success', 'You have successfully logged in!');
    }
    public function logout(Request $request)
    {
        $id = Auth()->user()->role_id;
        if ($id != 1) {
            $where = ['user_id' => Auth()->user()->id, 'attendance_date' => Carbon::now()->format('Y-m-d')];
            $attendance = Attendance::where($where)->first();
            if ($attendance !== null) {
                if ($attendance->login_time != null) {
                    $overtime = UtilityHelper::getTotalOverTime(Auth()->user()->id);
                    $logout = Carbon::now();
                    $totalWorkHour = UtilityHelper::getHourBetweenTwoTimes($attendance->login_time, $logout, Auth()->user()->id);
                    $status = "0";
                    if ((int) $totalWorkHour < 8) {
                        $status = "2";
                    } else if ((int) $totalWorkHour > 8) {
                        $status = "1";
                    }
                    $data = [
                        'logout_time' => Carbon::now(),
                        'over_time' => $overtime,
                        'status' => $status,
                    ];
                    Attendance::where($where)->update($data);
                    Log::create([
                        'user_id' => Auth()->user()->id,
                        'module' => 'Logout',
                        'description' => Auth()->user()->name . " Logged Out"
                    ]);
                }
            }
        }
        $this->guard()->logout();
        return redirect()->route('login')->with('success', 'You have successfully logout.');
    }
}
