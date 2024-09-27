<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Hash;
use Session;
use App\Models\RoleUser;
use App\Models\User;
use App\Models\Log;
use App\Models\Setting;
use App\Models\Covernote;
use App\Models\Policy;
use App\Models\Claim;
use App\Models\PayoutList;
use App\Models\SourcingAgent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function __construct()
    {
        $setting = Setting::first();
        $user = User::first();
        view()->share('setting', $setting);
    }
    public function dashboard(Request $req)
    {
        $page = 'Dashboard';
        $icon = 'dashboard.png';
        if (Auth::user()->role != 3) {
            $today = Carbon::today();
            $totalPremium = Policy::where('status', 1)->whereDate('created_at', $today)->sum('net_premium_amount');
            $sales_overview['npa'] = $totalPremium;
            $sales_overview['nop'] = Policy::where('status', 1)->whereDate('created_at', $today)->count();
            $sales_overview['motor'] = Policy::where('insurance_type', 1)->where('category', 1)->whereDate('created_at', $today)->where('status', 1)->count();
            $sales_overview['nonmotor'] = Policy::where('insurance_type', 1)->where('category', 2)->whereDate('created_at', $today)->where('status', 1)->count();
            $sales_overview['helth'] = Policy::where('insurance_type', 2)->where('status', 1)->whereDate('created_at', $today)->count();
            $sales_overview['renewal'] = Policy::where('business_type', '=', 2)->where('status', 1)->whereDate('created_at', $today)->count();
            $sales_overview['new'] = Policy::where('business_type', '!=', 2)->where('status', 1)->whereDate('created_at', $today)->count();

            // till month
            $startOfMonth = Carbon::now()->startOfMonth();
            $totalPremium = Policy::where('status', 1)->whereBetween('created_at', [$startOfMonth, $today])->sum('net_premium_amount');
            $month_overview['npa'] = $totalPremium;
            $month_overview['nop'] = Policy::where('status', 1)->whereBetween('created_at', [$startOfMonth, $today])->count();
            $month_overview['motor'] = Policy::where('insurance_type', 1)->where('category', 1)->whereBetween('created_at', [$startOfMonth, $today])->where('status', 1)->count();
            $month_overview['nonmotor'] = Policy::where('insurance_type', 1)->where('category', 2)->whereBetween('created_at', [$startOfMonth, $today])->where('status', 1)->count();
            $month_overview['helth'] = Policy::where('insurance_type', 2)->where('status', 1)->whereBetween('created_at', [$startOfMonth, $today])->count();
            $month_overview['renewal'] = Policy::where('business_type', '=', 2)->where('status', 1)->whereBetween('created_at', [$startOfMonth, $today])->count();
            $month_overview['new'] = Policy::where('business_type', '!=', 2)->where('status', 1)->whereBetween('created_at', [$startOfMonth, $today])->count();

            // till year
            $currentDate = Carbon::now();
            $financialYearStart = Carbon::create($currentDate->year - 1, 4, 1); // Assuming financial year starts on April 1st
            $financialYearEnd = Carbon::create($currentDate->year, 3, 31); // Assuming financial year ends on March 31st

            // Check if the current date is before April 1st, adjust the year accordingly
            if ($currentDate->month < 4) {
                $financialYearStart->subYear();
                $financialYearEnd->subYear();
            }
            $totalPremium = Policy::where('status', 1)->where(function ($query) use ($financialYearStart, $financialYearEnd) {
                $query->whereBetween('risk_start_date', [$financialYearStart, $financialYearEnd])
                    ->orWhereBetween('risk_end_date', [$financialYearStart, $financialYearEnd]);
            })->sum('net_premium_amount');
            $year_overview['npa'] = $totalPremium;
            $year_overview['nop'] = Policy::where('status', 1)->where(function ($query) use ($financialYearStart, $financialYearEnd) {
                $query->whereBetween('risk_start_date', [$financialYearStart, $financialYearEnd])
                    ->orWhereBetween('risk_end_date', [$financialYearStart, $financialYearEnd]);
            })->count();
            $year_overview['motor'] = Policy::where('insurance_type', 1)->where('category', 1)->where(function ($query) use ($financialYearStart, $financialYearEnd) {
                $query->whereBetween('risk_start_date', [$financialYearStart, $financialYearEnd])
                    ->orWhereBetween('risk_end_date', [$financialYearStart, $financialYearEnd]);
            })->where('status', 1)->count();
            $year_overview['nonmotor'] = Policy::where('insurance_type', 1)->where('category', 2)->where(function ($query) use ($financialYearStart, $financialYearEnd) {
                $query->whereBetween('risk_start_date', [$financialYearStart, $financialYearEnd])
                    ->orWhereBetween('risk_end_date', [$financialYearStart, $financialYearEnd]);
            })->where('status', 1)->count();
            $year_overview['helth'] = Policy::where('insurance_type', 2)->where('status', 1)->where(function ($query) use ($financialYearStart, $financialYearEnd) {
                $query->whereBetween('risk_start_date', [$financialYearStart, $financialYearEnd])
                    ->orWhereBetween('risk_end_date', [$financialYearStart, $financialYearEnd]);
            })->count();
            $year_overview['renewal'] = Policy::where('business_type', '=', 2)->where('status', 1)->where(function ($query) use ($financialYearStart, $financialYearEnd) {
                $query->whereBetween('risk_start_date', [$financialYearStart, $financialYearEnd])
                    ->orWhereBetween('risk_end_date', [$financialYearStart, $financialYearEnd]);
            })->count();
            $year_overview['new'] = Policy::where('business_type', '!=', 2)->where('status', 1)->where(function ($query) use ($financialYearStart, $financialYearEnd) {
                $query->whereBetween('risk_start_date', [$financialYearStart, $financialYearEnd])
                    ->orWhereBetween('risk_end_date', [$financialYearStart, $financialYearEnd]);
            })->count();

            //   till Finance

            $startOfLastFinancialYear = Carbon::createFromDate($today->year - 1, 4, 1);
            $endOfLastFinancialYear = Carbon::createFromDate($today->year, 3, 31);

            // Check if the current date is before April 1st, and adjust the year accordingly
            if ($today->lt(Carbon::createFromDate($today->year, 4, 1))) {
                $startOfLastFinancialYear = Carbon::createFromDate($today->year - 2, 4, 1);
                $endOfLastFinancialYear = Carbon::createFromDate($today->year - 1, 3, 31);
            }

            $totalPremium = Policy::where('status', 1)
                ->where(function ($query) use ($startOfLastFinancialYear, $endOfLastFinancialYear) {
                    $query->whereBetween('risk_start_date', [$startOfLastFinancialYear, $endOfLastFinancialYear])
                        ->orWhereBetween('risk_end_date', [$startOfLastFinancialYear, $endOfLastFinancialYear]);
                })
                ->sum('net_premium_amount');
            $finance_overview['npa'] = $totalPremium;

            // Get the count of policies with risk dates within the last financial year
            $finance_overview['nop'] = Policy::where('status', 1)
                ->where(function ($query) use ($startOfLastFinancialYear, $endOfLastFinancialYear) {
                    $query->whereBetween('risk_start_date', [$startOfLastFinancialYear, $endOfLastFinancialYear])
                        ->orWhereBetween('risk_end_date', [$startOfLastFinancialYear, $endOfLastFinancialYear]);
                })
                ->count();

            // Get the count of motor policies with risk dates within the last financial year
            $finance_overview['motor'] = Policy::where('insurance_type', 1)
                ->where('category', 1)
                ->where('status', 1)
                ->where(function ($query) use ($startOfLastFinancialYear, $endOfLastFinancialYear) {
                    $query->whereBetween('risk_start_date', [$startOfLastFinancialYear, $endOfLastFinancialYear])
                        ->orWhereBetween('risk_end_date', [$startOfLastFinancialYear, $endOfLastFinancialYear]);
                })
                ->count();

            // Get the count of non-motor policies with risk dates within the last financial year
            $finance_overview['nonmotor'] = Policy::where('insurance_type', 1)
                ->where('category', 2)
                ->where('status', 1)
                ->where(function ($query) use ($startOfLastFinancialYear, $endOfLastFinancialYear) {
                    $query->whereBetween('risk_start_date', [$startOfLastFinancialYear, $endOfLastFinancialYear])
                        ->orWhereBetween('risk_end_date', [$startOfLastFinancialYear, $endOfLastFinancialYear]);
                })
                ->count();

            // Get the count of health policies with risk dates within the last financial year
            $finance_overview['helth'] = Policy::where('insurance_type', 2)
                ->where('status', 1)
                ->where(function ($query) use ($startOfLastFinancialYear, $endOfLastFinancialYear) {
                    $query->whereBetween('risk_start_date', [$startOfLastFinancialYear, $endOfLastFinancialYear])
                        ->orWhereBetween('risk_end_date', [$startOfLastFinancialYear, $endOfLastFinancialYear]);
                })
                ->count();

            // Get the count of renewal policies with risk dates within the last financial year
            $finance_overview['renewal'] = Policy::where('business_type', 2)
                ->where('status', 1)
                ->where(function ($query) use ($startOfLastFinancialYear, $endOfLastFinancialYear) {
                    $query->whereBetween('risk_start_date', [$startOfLastFinancialYear, $endOfLastFinancialYear])
                        ->orWhereBetween('risk_end_date', [$startOfLastFinancialYear, $endOfLastFinancialYear]);
                })
                ->count();

            // Get the count of new policies with risk dates within the last financial year
            $finance_overview['new'] = Policy::where('business_type', '!=', 2)
                ->where('status', 1)
                ->where(function ($query) use ($startOfLastFinancialYear, $endOfLastFinancialYear) {
                    $query->whereBetween('risk_start_date', [$startOfLastFinancialYear, $endOfLastFinancialYear])
                        ->orWhereBetween('risk_end_date', [$startOfLastFinancialYear, $endOfLastFinancialYear]);
                })
                ->count();
            if ($req->has('from_date') && $req->has('to_date')) {
                $from_date = date("Y-m-d", strtotime($req->from_date));
                $to_date = date("Y-m-d", strtotime($req->to_date));
                $covernotes = Covernote::orderBy('id', 'Desc')->whereBetween('risk_start_date', [$from_date, $to_date])->count();
                $renewal = Policy::where('business_type', 2)->orderBy('id', 'Desc')->whereBetween('risk_start_date', [$from_date, $to_date])->count();
                $agents = SourcingAgent::where('status', 1)->whereBetween('created_at', [$from_date, $to_date])->count();
                $health_policy = Policy::where('insurance_type', 2)->where('status', 1)->whereBetween('created_at', [$from_date, $to_date])->count();
                $vehicle_policy = Policy::where('insurance_type', 1)->where('status', 1)->whereBetween('created_at', [$from_date, $to_date])->count();
                $payout_list = PayoutList::where('status', 1)->whereBetween('created_at', [$from_date, $to_date])->count();

                $totalPremium = Policy::where('status', 1)->whereBetween('risk_start_date', [$from_date, $to_date])->sum('net_premium_amount');
                $sales_overview['npa'] = $totalPremium;
                $sales_overview['nop'] = Policy::where('status', 1)->whereBetween('risk_start_date', [$from_date, $to_date])->count();
                $sales_overview['motor'] = Policy::where('insurance_type', 1)->where('category', 1)->where('status', 1)->whereBetween('risk_start_date', [$from_date, $to_date])->count();
                $sales_overview['nonmotor'] = Policy::where('insurance_type', 1)->where('category', 2)->where('status', 1)->whereBetween('risk_start_date', [$from_date, $to_date])->count();
                $sales_overview['helth'] = Policy::where('insurance_type', 2)->where('status', 1)->whereBetween('risk_start_date', [$from_date, $to_date])->count();
                $sales_overview['renewal'] = Policy::where('business_type', '=', 2)->where('status', 1)->whereBetween('risk_start_date', [$from_date, $to_date])->count();
                $sales_overview['new'] = Policy::where('business_type', '!=', 2)->where('status', 1)->whereBetween('risk_start_date', [$from_date, $to_date])->count();
            } else {
                $covernotes = Covernote::orderBy('id', 'Desc')->whereMonth('risk_start_date', Carbon::now()->month)->count();
                $renewal = Policy::where('business_type', 2)->orderBy('id', 'Desc')->whereMonth('risk_start_date', Carbon::now()->month)->count();
                $agents = SourcingAgent::where('status', 1)->whereMonth('created_at', Carbon::now()->month)->count();
                $health_policy = Policy::where('insurance_type', 2)->where('status', 1)->whereMonth('created_at', Carbon::now()->month)->count();
                $vehicle_policy = Policy::where('insurance_type', 1)->where('status', 1)->whereMonth('created_at', Carbon::now()->month)->count();
                $payout_list = PayoutList::where('status', 1)->whereMonth('created_at', Carbon::now()->month)->count();
            }
            if ($req->has('lob_month') && $req->has('lob_year')) {
                $lob = Policy::join('categories as sub_cat', 'sub_cat.id', 'policies.sub_category')->select('sub_cat.name', DB::raw('sum(net_premium_amount) as total'))->where('policies.status', 1)->where('policies.sub_category', '!=', 0)->groupBy('policies.sub_category')->whereMonth('policies.risk_start_date', $req->lob_month)->whereYear('policies.risk_start_date', $req->lob_year)->get();
            } else {
                $lob = Policy::join('categories as sub_cat', 'sub_cat.id', 'policies.sub_category')->select('sub_cat.name', DB::raw('sum(net_premium_amount) as total'))->where('policies.status', 1)->where('policies.sub_category', '!=', 0)->groupBy('policies.sub_category')->whereMonth('policies.risk_start_date', Carbon::now()->month)->whereYear('policies.risk_start_date', 2019)->get();
                // $lob = Policy::join('categories as sub_cat','sub_cat.id','policies.sub_category')->select('sub_cat.name',DB::raw('sum(net_premium_amount) as total'))->where('policies.status',1)->where('policies.sub_category','!=',0)->groupBy('policies.sub_category')->whereMonth('policies.risk_start_date', Carbon::now()->month)->whereYear('policies.risk_start_date', Carbon::now()->year)->get();
            }
            if ($req->has('p_lob_month') && $req->has('p_lob_year')) {
                $last_month_lob = Policy::join('categories as sub_cat', 'sub_cat.id', 'policies.sub_category')->select('sub_cat.name', DB::raw('sum(net_premium_amount) as total'))->where('policies.status', 1)->where('policies.sub_category', '!=', 0)->groupBy('policies.sub_category')->whereMonth('policies.risk_start_date', $req->p_lob_month)->whereYear('policies.risk_start_date', $req->p_lob_year)->get();
            } else {
                $last_month_lob = Policy::join('categories as sub_cat', 'sub_cat.id', 'policies.sub_category')->select('sub_cat.name', DB::raw('sum(net_premium_amount) as total'))->where('policies.status', 1)->where('policies.sub_category', '!=', 0)->groupBy('policies.sub_category')->whereMonth('policies.risk_start_date', Carbon::now()->subMonth())->whereYear('policies.risk_start_date', 2018)->get();
                // $last_month_lob = Policy::join('categories as sub_cat','sub_cat.id','policies.sub_category')->select('sub_cat.name',DB::raw('sum(net_premium_amount) as total'))->where('policies.status',1)->where('policies.sub_category','!=',0)->groupBy('policies.sub_category')->whereMonth('policies.risk_start_date', Carbon::now()->subMonth())->whereYear('policies.risk_start_date', date('Y')-1)->get();

            }
            $claims = Claim::orderBy('id', 'Desc')->with('policy')->get();
            $stacked = array();
            for ($i = 1; $i <= 12; $i++) {
                if ($req->has('current_year')) {
                    $policy = Policy::join('categories as sub_cat', 'sub_cat.id', 'policies.sub_category')->select('sub_cat.name', DB::raw('sum(net_premium_amount) as total'))->where('policies.status', 1)->where('policies.sub_category', '!=', 0)->groupBy('policies.sub_category')->whereMonth('policies.risk_start_date', '=', $i)->whereYear('policies.risk_start_date', $req->current_year)->get();
                } else {
                    $policy = Policy::join('categories as sub_cat', 'sub_cat.id', 'policies.sub_category')->select('sub_cat.name', DB::raw('sum(net_premium_amount) as total'))->where('policies.status', 1)->where('policies.sub_category', '!=', 0)->groupBy('policies.sub_category')->whereMonth('policies.risk_start_date', '=', $i)->whereYear('policies.risk_start_date', 2019)->get();
                    // $policy = Policy::join('categories as sub_cat','sub_cat.id','policies.sub_category')->select('sub_cat.name',DB::raw('sum(net_premium_amount) as total'))->where('policies.status',1)->where('policies.sub_category','!=',0)->groupBy('policies.sub_category')->whereMonth('policies.risk_start_date', '=', $i)->whereYear('policies.risk_start_date', date('Y'))->get();
                }
                if (!blank($policy)) {
                    $j = 0;
                    foreach ($policy as $policyData) {
                        // $stacked[$i][$j]['category'] = $policyData->name;
                        $stacked[$i][$policyData->name]    = $policyData->total;
                        $j++;
                    }
                } else {
                    $stacked[$i]['null'] = 0;
                }
            }
            $cat = array();
            foreach ($stacked as $key => $stack) {
                if ($stack != 0) {
                    foreach ($stack as $k => $sk) {
                        if (!in_array($k, $cat)) {
                            array_push($cat, $k);
                        }
                    }
                }
            }
            $ar = [];
            foreach ($stacked as $key => $stack) {
                if ($stack != 0) {
                    foreach ($cat as $ca) {
                        // echo $ca;
                        if (array_key_exists($ca, $stack)) {
                            $ar[$ca][$key] = $stack[$ca];
                        } else {
                            $ar[$ca][$key] = 0;
                        }
                    }
                }
            }
            //second
            $stacked1 = array();
            for ($i = 1; $i <= 12; $i++) {
                if ($req->has('previous_year')) {
                    $policy1 = Policy::join('categories as sub_cat', 'sub_cat.id', 'policies.sub_category')->select('sub_cat.name', DB::raw('sum(net_premium_amount) as total'))->where('policies.status', 1)->where('policies.sub_category', '!=', 0)->groupBy('policies.sub_category')->whereMonth('policies.risk_start_date', $i)->whereYear('policies.risk_start_date', $req->previous_year)->get();
                } else {
                    $policy1 = Policy::join('categories as sub_cat', 'sub_cat.id', 'policies.sub_category')->select('sub_cat.name', DB::raw('sum(net_premium_amount) as total'))->where('policies.status', 1)->where('policies.sub_category', '!=', 0)->groupBy('policies.sub_category')->whereMonth('policies.risk_start_date', $i)->whereYear('policies.risk_start_date', 2018)->get();
                    // $policy1 = Policy::join('categories as sub_cat','sub_cat.id','policies.sub_category')->select('sub_cat.name',DB::raw('sum(net_premium_amount) as total'))->where('policies.status',1)->where('policies.sub_category','!=',0)->groupBy('policies.sub_category')->whereMonth('policies.risk_start_date', $i)->whereYear('policies.risk_start_date', date('Y')-1)->get();
                }
                if (!blank($policy1)) {
                    $j = 0;
                    foreach ($policy1 as $policyData1) {
                        // $stacked[$i][$j]['category'] = $policyData->name;
                        $stacked1[$i][$policyData1->name]    = $policyData1->total;
                        $j++;
                    }
                } else {
                    $stacked1[$i]['null'] = 0;
                }
            }
            $cat1 = array();
            foreach ($stacked1 as $key => $stack) {
                if ($stack != 0) {
                    foreach ($stack as $k => $sk) {
                        if (!in_array($k, $cat1)) {
                            array_push($cat1, $k);
                        }
                    }
                }
            }
            $ar1 = [];
            foreach ($stacked1 as $key => $stack) {
                if ($stack != 0) {
                    foreach ($cat1 as $ca) {
                        // echo $ca;
                        if (array_key_exists($ca, $stack)) {
                            $ar1[$ca][$key] = $stack[$ca];
                        } else {
                            $ar1[$ca][$key] = 0;
                        }
                    }
                }
            }
            $chart = array();
            foreach ($ar as $key => $data1) {
                $clr = str_pad(dechex(rand(0x000000, 0xFFFFFF)), 6, 0, STR_PAD_LEFT);
                $chart1 = array();
                if ($req->has('current_year')) {
                    $label = $req->current_year;
                } else {
                    $label = date('Y');
                }
                $chart1['label'] = $label . ' - ' . $key;
                $chart1['backgroundColor'] = '#' . $clr;
                $chart1['borderWidth'] = 0;
                $chart1['stack'] = 'current';
                // $data11 = array_values($data);
                $chart1['data'] = array_values($data1);
                array_push($chart, $chart1);
            }
            foreach ($ar1 as $key => $data1) {
                $clr = str_pad(dechex(rand(0x000000, 0xFFFFFF)), 6, 0, STR_PAD_LEFT);
                $chart1 = array();
                if ($req->has('previous_year')) {
                    $label = $req->previous_year;
                } else {
                    $label = date('Y');
                }
                $chart1['label'] = $label . ' - ' . $key;
                $chart1['backgroundColor'] = '#' . $clr;
                $chart1['borderWidth'] = 0;
                $chart1['stack'] = 'previous';
                // $data11 = array_values($data);
                $chart1['data'] = array_values($data1);
                array_push($chart, $chart1);
            }


            return view('admin.dashboard', compact('startOfLastFinancialYear','endOfLastFinancialYear','finance_overview','year_overview', 'month_overview', 'page', 'sales_overview', 'icon', 'covernotes', 'claims', 'renewal', 'lob', 'last_month_lob', 'stacked', 'chart', 'agents', 'health_policy', 'vehicle_policy', 'payout_list'));
        } else {
            $health_policy = Policy::where('insurance_type', 2)->where('agent', Auth::user()->id)->where('status', 1)->orderBy('id', 'DESC')->count();
            $vehicle_policy = Policy::where('insurance_type', 1)->where('agent', Auth::user()->id)->where('status', 1)->orderBy('id', 'DESC')->count();
            return view('admin.dashboard', compact('page', 'icon', 'health_policy', 'vehicle_policy'));
        }
    }
    protected function formatNumber($num)
    {
        if ($num >= 10000000) {
            $formatted = number_format($num / 10000000, 2) . 'Cr';
        } elseif ($num >= 100000) {
            $formatted = number_format($num / 100000, 2) . 'L';
        } elseif ($num >= 1000) {
            $formatted = number_format($num / 1000, 2) . 'K';
        } else {
            $formatted = number_format($num, 2);
        }
        return $formatted;
    }
    public function logs()
    {
        $logs = Log::orderBy('id', 'Desc')->get();
        $page       = 'Logs';
        $icon       = 'logs.png';
        return view('admin.logs.logs', compact('logs', 'page', 'icon'));
    }
    public function edit_profile()
    {
        $userId = Auth::check() ? Auth::id() : true;
        $user = User::where('id', $userId)->first();
        $page       = 'Edit Profile';
        $icon       = 'profile.png';
        // if(Auth::user()->role == 1){
        return view('admin/profile/edit_profile', compact('user', 'page', 'icon'));
        // }else{
        //     return view('agent/profile/edit_profile',compact('user','page','icon'));
        // }
    }
    public function view_profile()
    {
        $userId     = Auth::check() ? Auth::id() : true;
        $user       = User::where('id', $userId)->first();
        $page       = 'Profile';
        $icon       = 'profile.png';
        // if(Auth::user()->role == 1){
        return view('admin/profile/view_profile', compact('user', 'page', 'icon'));
        // }else{
        //     return view('agent/profile/view_profile',compact('user','page','icon'));
        // }
    }
}
