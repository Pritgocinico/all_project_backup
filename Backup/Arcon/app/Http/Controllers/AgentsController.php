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
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Notification;
use App\Notifications\Notifications;
use LaravelDaily\LaravelCharts\Classes\LaravelChart;
class AgentsController extends Controller
{
    public function __construct() {
        $setting=Setting::first();
        $user = User::first();
        $notification = $user->unreadNotifications;
        view()->share('notifications', $notification);
        view()->share('setting', $setting);
    }
    public function dashboard(Request $req){
        $page = 'Agent Dashboard';
        $icon = 'dashboard.png';
        $date = explode(" - ", request()->input('from-to', ""));

        // if(count($date) != 2)
        // {
            $date = [now()->subDays(29)->format("d-m-Y"), now()->format("d-m-Y")];
        // }

      
        if($req->has('start_date') || $req->has('end_date')){
              $settings = [
                'chart_title'           => 'Sales',
                'chart_type'            => 'line',
                'report_type'           => 'group_by_date',
                'model'                 => 'App\Models\Order',
                // 'relationship_name'     => 'user', // represents function user() on Transaction model
                'group_by_field'        => 'created_at',
                'group_by_period'       => 'day',
                'aggregate_function'    => 'sum',
                'aggregate_field'       => 'total',
                'filter_field'          => 'created_at',
                'range_date_start'=>$req->start_date,
                'range_date_end'=>$req->end_date,
                // 'range_date_start'      => $date[0],
                // 'range_date_end'        => $date[1],
                'group_by_field_format' => 'Y-m-d H:i:s',
                'column_class'          => 'col-md-12',
                // 'entries_number'        => '5',
                // 'filter_days'           => 30,
                'continuous_time'       => true,
            ];
            $start = Carbon::createFromFormat('d-m-Y', $date[0]);
            $end = Carbon::createFromFormat('d-m-Y', $date[1]);
            $chart = new LaravelChart($settings);
            $users = Order::select(DB::raw("sum(orders.total) as count"), DB::raw("Date(orders.created_at) as month_name"))
                    // ->whereYear('orders.created_at', date('Y'))
                    ->join('users as u1','orders.user_id','=','u1.id')
                    ->where('u1.agent',Auth::user()->id)
                    ->whereBetween('orders.created_at', [$req->start_date,$req->end_date])
                    ->groupBy(DB::raw("Date(orders.created_at)"))
                    ->pluck('count', 'month_name');
            // print_r($users);
            // exit;
            $labels = $users->keys();
            $data = $users->values();
    
                $visitors = OrderItem::
                select('e1.name',
                        DB::raw('count(DISTINCT o1.id) as total_orders'))
                ->join('orders as o1','order_items.order_id','=','o1.id')
                ->join('users as u1','o1.user_id','=','u1.id')
                ->join('products as e1','order_items.product_id','=','e1.id')
                ->join('product_variants as e3','order_items.variant_id','=','e3.id')
                ->where('u1.agent',Auth::user()->id)
                ->whereBetween('o1.created_at', [$req->start_date,$req->end_date])
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
                ->join('users as u1','o1.user_id','=','u1.id')
                ->join('products as e1','order_items.product_id','=','e1.id')
                ->join('product_variants as e3','order_items.variant_id','=','e3.id')
                ->where('u1.agent',Auth::user()->id)
                ->whereBetween('o1.created_at', [$req->start_date,$req->end_date])
                ->groupBy('e1.name')
                ->get();
    
                $revenue[] = ['Products','Revenue'];
                foreach ($revenues as $key => $value) {
                    $revenue[++$key] = [$value->name, (int)$value->sum];
                }
        }else{
            $settings = [
                'chart_title'           => 'Sales',
                'chart_type'            => 'line',
                'report_type'           => 'group_by_date',
                'model'                 => 'App\Models\Order',
                // 'relationship_name'     => 'user', // represents function user() on Transaction model
                'group_by_field'        => 'created_at',
                'group_by_period'       => 'day',
                'aggregate_function'    => 'sum',
                'aggregate_field'       => 'total',
                'filter_field'          => 'created_at',
                'filter_period' => 'month',
                // 'range_date_start'=>$req->start_date,
                // 'range_date_end'=>$req->end_date,
                'range_date_start'      => $date[0],
                'range_date_end'        => $date[1],
                'group_by_field_format' => 'Y-m-d H:i:s',
                'column_class'          => 'col-md-12',
                // 'entries_number'        => '5',
                'filter_days'           => 30,
                // 'continuous_time'       => true,
            ];
            $start = Carbon::createFromFormat('d-m-Y', $date[0]);
            $end = Carbon::createFromFormat('d-m-Y', $date[1]);
            $chart = new LaravelChart($settings);
            $users = Order::select(DB::raw("sum(orders.total) as count"), DB::raw("Date(orders.created_at) as month_name"))
                    // ->whereYear('orders.created_at', date('Y'))
                    ->join('users as u1','orders.user_id','=','u1.id')
                    ->where('u1.agent',Auth::user()->id)
                    // ->whereBetween('orders.created_at', [$start,$end])
                    ->whereDate('orders.created_at', Carbon::today())
                    ->groupBy(DB::raw("Date(orders.created_at)"))
                    ->pluck('count', 'month_name');
            // print_r($users);
            // exit;
            $labels = $users->keys();
            $data = $users->values();
    
                $visitors = OrderItem::
                select('e1.name',
                        DB::raw('count(DISTINCT o1.id) as total_orders'))
                ->join('orders as o1','order_items.order_id','=','o1.id')
                ->join('users as u1','o1.user_id','=','u1.id')
                ->join('products as e1','order_items.product_id','=','e1.id')
                ->join('product_variants as e3','order_items.variant_id','=','e3.id')
                ->where('u1.agent',Auth::user()->id)
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
                ->join('users as u1','o1.user_id','=','u1.id')
                ->join('products as e1','order_items.product_id','=','e1.id')
                ->join('product_variants as e3','order_items.variant_id','=','e3.id')
                ->where('u1.agent',Auth::user()->id)
                ->whereDate('o1.created_at', Carbon::today())
                ->groupBy('e1.name')
                ->get();
    
                $revenue[] = ['Products','Revenue'];
                foreach ($revenues as $key => $value) {
                    $revenue[++$key] = [$value->name, (int)$value->sum];
                }
        }
        return view('agent.dashboard',compact('page','icon','chart','result','revenue','labels','data'));
    }
}
