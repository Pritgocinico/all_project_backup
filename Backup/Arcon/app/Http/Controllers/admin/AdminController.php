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
use App\Models\Inquiry;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Notification;
use App\Notifications\Notifications;
use LaravelDaily\LaravelCharts\Classes\LaravelChart;
use Carbon\Carbon;
class AdminController extends Controller
{
    public function __construct() {
        $setting=Setting::first();
        $user = User::first();
        $notification = $user->unreadNotifications;
        view()->share('notifications', $notification);
        view()->share('setting', $setting);
    }
    public function dashboard(Request $req){
        $page = 'Admin Dashboard';
        $icon = 'dashboard.png';
        $date = explode(" - ", request()->input('from-to', ""));

        if(count($date) != 2)
        {
            $date = [now()->subDays(29)->format("d-m-Y"), now()->format("d-m-Y")];
        }

        $settings = [
            'chart_title'           => 'Sales',
            'chart_type'            => 'line',
            'report_type'           => 'group_by_date',
            'model'                 => 'App\Models\Order',
            'group_by_field'        => 'created_at',
            'group_by_period'       => 'day',
            'aggregate_function'    => 'sum',
            'aggregate_field'       => 'total',
            'filter_field'          => 'created_at',
            'range_date_start'      => $date[0],
            'range_date_end'        => $date[1],
            'group_by_field_format' => 'Y-m-d H:i:s',
            'column_class'          => 'col-md-12',
            // 'entries_number'        => '5',
            'filter_days'           => 30,
            'continuous_time'       => true,
        ];

        $chart = new LaravelChart($settings);
            $visitors = OrderItem::
            select('e1.name',
                    DB::raw('count(DISTINCT o1.id) as total_orders'))
            ->join('orders as o1','order_items.order_id','=','o1.id')
            ->join('products as e1','order_items.product_id','=','e1.id')
            ->join('product_variants as e3','order_items.variant_id','=','e3.id')
            ->whereDate('o1.created_at', Carbon::today())
            ->groupBy('e1.name')
            ->get();

            $result[] = ['Products','Orders'];
            foreach ($visitors as $key => $value) {
                $result[++$key] = [$value->name, (int)$value->total_orders];
            }
            $revenues = OrderItem::
            select('e1.name',
                    DB::raw('sum(order_items.total) as sum'))
            ->join('orders as o1','order_items.order_id','=','o1.id')
            ->join('products as e1','order_items.product_id','=','e1.id')
            ->join('product_variants as e3','order_items.variant_id','=','e3.id')
            ->whereDate('o1.created_at', Carbon::today())
            ->groupBy('e1.name')
            ->get();

            $revenue[] = ['Products','Revenue'];
            foreach ($revenues as $key => $value) {
                $revenue[++$key] = [$value->name, (int)$value->sum];
            }
        return view('admin.dashboard',compact('page','icon','chart','result','revenue'));
    }
    public function logs(){
        $logs = Log::orderBy('id','Desc')->get();
        $page       = 'Logs';
        $icon       = 'logs.png';
        return view('admin.logs.logs',compact('logs','page','icon'));
    }
    public function inquiry(){
        $inquiries  = Inquiry::orderBy('id','Desc')->get();
        $page       = 'Inquiry';
        $icon       = 'inquiry.png';
        return view('admin.inquiry.inquiries',compact('inquiries','page','icon'));
    }
    public function edit_profile(){
        $userId = Auth::check() ? Auth::id() : true;
        $user=User::where('id',$userId)->first();
        $page       = 'Profile';
        $icon       = 'profile.png';
        if(Auth::user()->role == 1){
            return view('admin/profile/edit_profile',compact('user','page','icon'));
        }else{
            return view('agent/profile/edit_profile',compact('user','page','icon')); 
        } 
    }
    public function view_profile(){
        $userId     = Auth::check() ? Auth::id() : true;
        $user       = User::where('id',$userId)->first();
        $page       = 'Profile';
        $icon       = 'profile.png';
        if(Auth::user()->role == 1){
            return view('admin/profile/view_profile',compact('user','page','icon'));
        }else{
            return view('agent/profile/view_profile',compact('user','page','icon'));
        }
    }
     public function deleteInquiry($id){
        $inquiry = Inquiry::where('id',$id);
       	$inquiry->delete();
       	$user = User::where('id',1)->first();
        $log = new Log();
        $log->user_id   = $user->name;
        $log->module    = 'inquiry';
        $log->log       = 'Inquiry has been Deleted Successfully.';
        $log->save();
        return 1;
    }
}
