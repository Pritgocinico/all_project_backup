<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Hash;
use Session;
use App\Models\RoleUser;
use App\Models\User;
use App\Models\Quotation;
use App\Models\Quotationfile;
use App\Models\QuotationUpload;
use App\Models\Customer;
use App\Models\WorkshopQuestion;
use App\Models\WorkshopDoneTask;
use App\Models\Project;
use App\Models\Log;
use App\Models\Setting;
use App\Models\TaskManagement;
use App\Models\Feedback;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use App\Models\AccessToken;
use Redirect;
use DateTime;

class DashboardController extends Controller
{

    public function __construct() {
        $setting=Setting::first();
        view()->share('setting', $setting);
    }

    public function dashboard(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required'
        ]);
        $authorization = $request->header('Authorization');
        $accessToken = AccessToken::where('access_token', $authorization)->first();
        $user = User::where('id', $accessToken->user_id)->first();
        $role = $user->role;
        if ($validator->fails()) {
            return response()->json(['status'=>0,'error'=>implode(" ", $validator->errors()->all())], 404);
        }else{
            $projects = Project::whereIn('status',[1,2])->count();
            $runningProjects = Project::where('status', 1)->get()->count();
            $completedProjects = Project::where('status', 2)->get()->count();
            $leads = Project::where('type', 0)->get()->count();
            $completedTasks = TaskManagement::where('task_status', 'completed')->where('deleted_at', null)->get()->count();
            $pendingTasks = TaskManagement::where('task_status', 'pending')->where('deleted_at', null)->get()->count();
            $feedback = Feedback::where('deleted_at', null)->count();
            $sales = Project::where('deleted_at', null)->sum('margin_cost');
            $currentMonth = Carbon::now()->month;
            $currentYear = Carbon::now()->year;

            $array = array();
            $array['projects'] = $projects;
            $array['running_projects'] = $runningProjects;
            $array['completed_projects'] = $completedProjects;
            $array['leads'] = $leads;
            $array['complated_tasks'] = $completedTasks;
            $array['pending_tasks'] = $pendingTasks;
            $array['feedback'] = $feedback;
            $array['sales'] = $sales;

            $monthWiseSales = [];
            for ($month = 1; $month <= 12; $month++) {
                $monthWiseSales[] = [
                    'month' => (string) $month,
                    'value' => '0',
                ];
            }

            if(!blank($request->dateRange)){
                $dateRange = $request->dateRange;
                $dateParts = explode(' - ', $dateRange);
                
                $startDate = DateTime::createFromFormat('d/m/Y', $dateParts[0]);
                if ($startDate !== false) {
                    $formattedStartDate = $startDate->format('Y-m-d');
                }

                $endDate = DateTime::createFromFormat('d/m/Y', $dateParts[1]);
                if ($endDate !== false) {
                    $formattedEndDate = $endDate->format('Y-m-d');
                }
                $salesData = Project::selectRaw('SUM(margin_cost) as total_margin_cost, MONTH(created_at) as month')
                ->whereNull('deleted_at')
                ->whereBetween('created_at', [$formattedStartDate, $formattedEndDate])
                ->groupBy('month')
                ->orderBy('month')
                ->get();
                
                foreach ($salesData as $sale) {
                    $monthWiseSales[$sale->month - 1]['value'] = (string) $sale->total_margin_cost;
                }
            }else{
                $salesData = Project::selectRaw('SUM(margin_cost) as total_margin_cost, MONTH(created_at) as month')
                ->whereNull('deleted_at')
                ->whereYear('created_at', $currentYear)
                ->groupBy('month')
                ->orderBy('month')
                ->get();
                foreach ($salesData as $sale) {
                    $monthWiseSales[$sale->month - 1]['value'] = (string) $sale->total_margin_cost;
                }
            }
            $array['month_wise_sales'] = $monthWiseSales;

            return response()->json(['status' => 1,'dashboard'=>$array],200);
        }
        return response()->json(['status'=>0,'error'=> 'Something went wrong.'],404);
    }
}