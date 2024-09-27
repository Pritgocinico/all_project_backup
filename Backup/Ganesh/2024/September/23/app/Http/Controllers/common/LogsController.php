<?php

namespace App\Http\Controllers\common;

use Nnjeim\World\WorldHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\User;
use App\Models\Customer;
use App\Models\Quotation;
use App\Models\Quotationfile;
use App\Models\Measurement;
use App\Models\Measurementfile;
use App\Models\Fitting;
use App\Models\TaskManagement;
use App\Models\Workshop;
use App\Models\WorkshopQuestion;
use App\Models\WorkshopDoneTask;
use App\Models\FittingQuestion;
use App\Models\FittingDoneTask;
use App\Models\Purchase;
use App\Models\Setting;
use App\Models\Log;
use Nnjeim\World\World;
use Carbon\Carbon;
use App\Models\Lead;
use Illuminate\Support\Facades\Auth;
use Notification;
use App\Notifications\OffersNotification;

class LogsController extends Controller
{
    protected $world;
    public function __construct()
    {
        $setting = Setting::first();
        view()->share('setting', $setting);
    }

    public function listLogs(Request $request){
        $logs = Log::where('user_id', Auth::user()->name)->orderBy('id', 'DESC')->get();
        $page = 'Log';

        if(Auth::user()->role == 1){
            return view('admin.logs.logs', compact('logs', 'page'));
        }else if(Auth::user()->role == 8) {
            return view('purchase.logs.logs', compact('logs', 'page'));
        }else if(Auth::user()->role == 10) {
            return view('quality_analytic.logs.logs', compact('logs', 'page'));
        }
        return view('quotation.logs.logs', compact('logs', 'page'));
    }
    
}
