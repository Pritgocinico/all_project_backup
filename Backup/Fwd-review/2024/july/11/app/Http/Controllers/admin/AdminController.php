<?php

namespace App\Http\Controllers\admin;

use App\Helpers\MemberHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Hash;
use Session;
use App\Models\RoleUser;
use App\Models\User;
use App\Models\Log;
use App\Models\Setting;
use App\Models\Business;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Notification;
use App\Notifications\OffersNotification;
use Illuminate\Validation\Rule;
use Mail;

class AdminController extends Controller
{
    private $apiUrl;
    private $apiKey;
    public function __construct()
    {
        $setting = Setting::first();
        $user = User::first();
        view()->share('setting', $setting);
        $this->apiUrl = env('MEMBER_PRESS_API_URL');
        $this->apiKey = env('MEMBER_PRESS_API_KEY');
    }
    public function dashboard(Request $request)
    {
        $page = 'Admin Dashboard';
        $icon = 'dashboard.png';
        $todaySubscription = Business::whereDate('created_at', date('Y-m-d'))->count();
        $todayExpire = Business::whereDate('sub_end_date', date('Y-m-d'))->count();
        $monthWiseClients = [];
        for ($month = 1; $month <= 12; $month++) {
            $monthWiseClients[$month] = 0;
        }

        $monthWiseBusinesses = [];
        for ($month = 1; $month <= 12; $month++) {
            $monthWiseBusinesses[$month] = 0;
        }

        if (!blank($request->selectedYear)) {
            $year = $request->selectedYear;
            $clients = User::where('role', 2)
                ->whereYear('created_at', $year)
                ->groupBy(DB::raw('MONTH(created_at)'))
                ->orderBy(DB::raw('MONTH(created_at)'))
                ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                ->get();
            foreach ($clients as $client) {
                $monthWiseClients[$client->month] = $client->count;
            }
            return response()->json($monthWiseClients, 200);
        } else {
            $year = Carbon::now()->year;
            $clients = User::where('role', 2)
                ->whereYear('created_at', $year)
                ->groupBy(DB::raw('MONTH(created_at)'))
                ->orderBy(DB::raw('MONTH(created_at)'))
                ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                ->get();
            foreach ($clients as $client) {
                $monthWiseClients[$client->month] = $client->count;
            }
        }

        if (!blank($request->businessYear)) {
            $year = $request->businessYear;
            $businesses = Business::whereYear('created_at', $year)
                ->where('active', 1)
                ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
                ->orderBy(DB::raw('YEAR(created_at)'), 'DESC')
                ->orderBy(DB::raw('MONTH(created_at)'))
                ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
                ->get();
            foreach ($businesses as $business) {
                $monthWiseBusinesses[$business->month] = $business->count;
            }
            return response()->json($monthWiseBusinesses, 200);
        } else {
            $year = Carbon::now()->year;
            $businesses = Business::whereYear('created_at', $year)
                ->where('active', 1)
                ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
                ->orderBy(DB::raw('YEAR(created_at)'), 'DESC')
                ->orderBy(DB::raw('MONTH(created_at)'))
                ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
                ->get();
            foreach ($businesses as $business) {
                $monthWiseBusinesses[$business->month] = $business->count;
            }
        }

        return view('admin.dashboard', compact('todaySubscription', 'todayExpire', 'page', 'icon', 'clients', 'businesses', 'monthWiseClients', 'monthWiseBusinesses'));
    }
    public function clients(Request $request)
    {
        $page = 'Clients';
        $icon = 'clients.png';
        $clients = User::withCount('businessData', 'paymentData')->where('role', 2)->get();
        $currDate = Carbon::now();
        $currentDate = $currDate->format('Y-m-d');
        return view('admin.clients.clients', compact('page', 'icon', 'clients', 'currentDate'));
    }
    public function addClient(Request $request)
    {
        $page = 'Clients';
        $icon = 'clients.png';
        return view('admin.clients.add_client', compact('page', 'icon'));
    }
    public function addClientData(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'phone' => 'required|unique:users,phone',
            'password' => 'required',
        ]);
        if ($request->status == "on") {
            $status = 1;
        } else {
            $status = 0;
        }
        $user = new User();
        $user->name         = $request->name;
        $user->phone        = $request->phone;
        $user->email        = $request->email;
        $user->role         = 2;
        $user->password     = Hash::make($request->password);
        $user->status       = 1;
        $insert             = $user->save();

        $role   =   new RoleUser;
        $role->user_id  = $user->id;
        $role->role_id  = 2;
        $ins            = $role->save();

        $admin = User::where('id', Auth::user()->id)->first();
        $notificationData = [
            'type' => 'message',
            'title' => 'Client Added - ',
            'text' => 'Name: ' . $request->name,
            'url' => route('admin.view.client', $user->id),
        ];

        Notification::send($admin, new OffersNotification($notificationData));

        $log = new Log();
        $log->user_id   = Auth::user()->name;
        $log->module    = 'Client';
        $log->log       = $user->name . ' Added Successfully';
        $log->save();

        if ($request->business == 1) {
            $userData = User::latest()->first();
            if ($insert) {
                return response()->json(['data' => $userData, 'message' => 'Client Added Successfully', 'status' => 1], 200);
            }
            return response()->json(['data' => "", 'message' => 'Something Went Wrong.', 'status' => 1], 500);
        } else {
            if ($insert) {
                $userData = User::latest()->first();
                $email = $userData->email;
                $data = [
                    'name' => $userData->first_name . " " . $userData->last_name,
                    'password' => $request->password,
                    'emailText' => $userData->email,
                ];
                Mail::send('emails.register', $data, function ($message) use ($email) {
                    $message->from('gofwdreviews@gmail.com', 'FWD Reviews')
                        ->to($email)
                        ->subject('Welcome to FWD Reviews');
                });
                Session::flash('success', 'Client Added Successfully.');
                return redirect()->route('admin.clients');
            } else {
                Session::flash('error', 'Something Went Wrong.');
                return redirect()->route('admin.add.client');
            }
        }
    }
    public function editClient(Request $request, $id = NULL)
    {
        $page = 'Clients';
        $icon = 'clients.png';
        $client = User::where('id', $id)->first();
        return view('admin.clients.edit_client', compact('page', 'icon', 'client'));
    }

    public function viewClient(Request $request, $id = NULL)
    {
        $page = 'Clients';
        $icon = 'clients.png';
        $client = User::where('id', $id)->first();
        $businesses = Business::where('client_id', $id)->get();
        return view('admin.clients.view_client', compact('businesses', 'page', 'icon', 'client'));
    }
    public function viewAdmin(Request $request)
    {
        $page = 'Admin';
        $icon = 'clients.png';
        $client = User::where('id', Auth()->user()->id)->first();
        return view('admin.profile.view', compact('page', 'icon', 'client'));
    }
    public function editAdmin(Request $request)
    {
        $page = 'Profile';
        $icon = 'clients.png';
        $client = User::where('id', Auth()->user()->id)->first();
        return view('admin.profile.edit', compact('page', 'icon', 'client'));
    }

    public function updateClient(Request $req)
    {
        $req->validate([
            'name' => 'required',
            'email' => [
                'required',
                Rule::unique('users')->ignore($req->user_id),
            ],
            'phone' => [
                'required',
                Rule::unique('users')->ignore($req->user_id),
            ],
        ]);
        if ($req->status == "on") {
            $status = 1;
        } else {
            $status = 0;
        }
        $user = User::where('id', $req->user_id)->first();
        $user->name     = $req->name;
        $user->phone    = $req->phone;
        $user->email    = $req->email;
        $name = explode(' ',$req->name);
        $data = [
            'first_name'=>$name[0],
            'last_name'=>$name[1]??"",
        ];
        if (isset($req->password)) {
            $data['password'] = $req->password;
            $user->password = Hash::make($req->password);
        }
        $user->status   = $status;
        $insert         = $user->save();
        MemberHelper::updateMember($user->member_id,$data);

        $admin = User::where('id', Auth::user()->id)->first();
        $notificationData = [
            'type' => 'message',
            'title' => 'Client Updated - ',
            'text' => 'Name: ' . $req->name,
            'url' => route('admin.view.client', $user->id),
        ];

        Notification::send($admin, new OffersNotification($notificationData));
        $log = new Log();
        $log->user_id   = Auth::user()->name;
        $log->module    = 'Client';
        $log->log       = $user->name . ' Updated Successfully';
        $log->save();
        if ($insert) {
            Session::flash('success', 'Client Updated Successfully.');
            return redirect()->route('admin.clients');
        } else {
            Session::flash('error', 'Something Went Wrong.');
            return redirect()->route('admin.edit.client', $user->id);
        }
    }
    public function updateAdmin(Request $req)
    {
        $req->validate([
            'name'                => 'required',
            'email'               => 'required',
            'phone'               => 'required|unique:users,phone,' . $req->user_id,
        ]);
        if ($req->status == "on") {
            $status = 1;
        } else {
            $status = 0;
        }
        $user = User::where('id', $req->user_id)->first();
        $user->name     = $req->name;
        $user->phone    = $req->phone;
        $user->email    = $req->email;
        if ($req->has('password') && $req->password !== null) {
            $user->password = Hash::make($req->password);
        }
        $user->status   = $status;
        $insert         = $user->save();

        $admin = User::where('id', Auth::user()->id)->first();
        $log = new Log();
        $log->user_id   = Auth::user()->name;
        $log->module    = 'Admin';
        $log->log       = $user->name . ' Updated Successfully';
        $log->save();
        Session::flash('success', 'Profile Updated Successfully.');
        return redirect()->route('admin.view');
    }
    public function deleteClient($id)
    {
        $user = User::where('id', $id)->first();
        $user->delete();

        $admin = User::where('id', Auth::user()->id)->first();
        $notificationData = [
            'type' => 'message',
            'title' => 'Client Deleted - ',
            'text' => 'Name: ' . $user->name,
            'url' => route('admin.clients'),
        ];

        Notification::send($admin, new OffersNotification($notificationData));

        $log = new Log();
        $log->user_id   = Auth::user()->name;
        $log->module    = 'Client';
        $log->log       = $user->name . ' Deleted Successfully';
        $log->save();
        if ($user) {
            return response()->json(['data' => '', 'message' => 'Client Deleted Successfully', 'status' => 1], 200);
        }
        return response()->json(['data' => '', 'message' => 'Something Went To Wrong.', 'status' => 1], 500);
    }

    public function paymentDetail(Request $request)
    {
        $page = 'Payment Details';
        $icon = 'clients.png';
        $paymentList = Payment::with('businessDetail', 'clientDetail')->get();
        return view('admin.payment.payment_detail', compact('paymentList', 'page', 'icon'));
    }

    public function dashboardCount(Request $request)
    {
        $date = $request->date_filter;
        $data['clients'] = User::where('role', 2)->when($date, function ($query) use ($date) {
            $date1 = explode('/', $date);
            $query->where('created_at', '>=', $date1[0])->where('created_at', '<=', $date1[1]);
        })->count();
        $data['business_count'] = Business::when($date, function ($query) use ($date) {
            $date1 = explode('/', $date);
            $query->where('created_at', '>=', $date1[0])->where('created_at', '<=', $date1[1]);
        })->where('active', 1)->count();
        $data['payment_count'] = Payment::when($date, function ($query) use ($date) {
            $date1 = explode('/', $date);
            $query->where('created_at', '>=', $date1[0])->where('created_at', '<=', $date1[1]);
        })->count();
        $data['total_amount'] = Payment::when($date, function ($query) use ($date) {
            $date1 = explode('/', $date);
            $query->where('created_at', '>=', $date1[0])->where('created_at', '<=', $date1[1]);
        })->sum('tax_amount');
        return $data;
    }
}
