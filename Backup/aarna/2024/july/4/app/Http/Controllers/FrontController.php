<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use Session;
use App\Models\User;
use App\Models\Log;
use Carbon\Carbon;
use App\Models\RoleUser;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
class FrontController extends Controller
{
    public function index(){
        return view('frontend.index');
    }
}
