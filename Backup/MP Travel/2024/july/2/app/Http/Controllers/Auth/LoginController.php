<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Log;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

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
    }
    protected function authenticated(Request $request, $user)
    {
        Log::create([
            'user_id' => $user->id,
            'module' => 'Login',
            'description' => $user->name . " Logged In"
        ]);
        return redirect()->intended($this->redirectPath())->with('success', 'You have successfully logged in!');
    }
}
