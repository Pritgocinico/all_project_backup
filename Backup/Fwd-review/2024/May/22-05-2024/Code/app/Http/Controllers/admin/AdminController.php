<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Hash;
use Session;
use App\Models\RoleUser;
use App\Models\User;
use App\Models\Log;
use App\Models\Setting;
use App\Models\Business;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Notification;
use App\Notifications\OffersNotification;

class AdminController extends Controller
{
    public function __construct() {
        $setting=Setting::first();
        $user = User::first();
        view()->share('setting', $setting);
    }
    public function dashboard(Request $request){
        $page = 'Admin Dashboard';
        $icon = 'dashboard.png';

        $monthWiseClients = [];
            for ($month = 1; $month <= 12; $month++) {
                $monthWiseClients[$month] = 0;
            }
        
        $monthWiseBusinesses = [];
        for ($month = 1; $month <= 12; $month++) {
            $monthWiseBusinesses[$month] = 0;
        }

        if(!blank($request->selectedYear)){
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
        }else{
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

        if(!blank($request->businessYear)){
            $year = $request->businessYear;
            $businesses = Business::whereYear('created_at', $year)
            ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
            ->orderBy(DB::raw('YEAR(created_at)'), 'DESC')
            ->orderBy(DB::raw('MONTH(created_at)'))
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
            ->get();
            foreach ($businesses as $business) {
                $monthWiseBusinesses[$business->month] = $business->count;
            }
            return response()->json($monthWiseBusinesses, 200);
        }else{
            $year = Carbon::now()->year;
            $businesses = Business::whereYear('created_at', $year)
            ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
            ->orderBy(DB::raw('YEAR(created_at)'), 'DESC')
            ->orderBy(DB::raw('MONTH(created_at)'))
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
            ->get();
            foreach ($businesses as $business) {
                $monthWiseBusinesses[$business->month] = $business->count;
            }
        }

        return view('admin.dashboard',compact('page','icon', 'clients', 'businesses', 'monthWiseClients', 'monthWiseBusinesses'));
    }
    public function clients(Request $request){
        $page = 'Clients';
        $icon = 'clients.png';
        $clients = User::where('role',2)->get();
        $currDate = Carbon::now();
        $currentDate = $currDate->format('Y-m-d');
        return view('admin.clients.clients',compact('page','icon','clients','currentDate'));
    }
    public function addClient(Request $request){
        $page = 'Clients';
        $icon = 'clients.png';
        return view('admin.clients.add_client',compact('page','icon'));
    }
    public function addClientData(Request $request){
        $request->validate([
            'name'      => 'required',
            'phone'     => 'required',
            'email'     => 'required',
            'password'  => 'required',
        ]);
        if($request->status == "on"){
            $status = 1;
        }else{
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

        $admin = User::where('id',Auth::user()->id)->first();
        $notificationData = [
            'type' => 'message',
            'title' => 'Client Added - ',
            'text' => 'Name: '.$request->name,
            'url' => route('admin.view.client',$user->id),
        ];
  
        Notification::send($admin, new OffersNotification($notificationData));

        $log = new Log();
        $log->user_id   = Auth::user()->name;
        $log->module    = 'Client';
        $log->log       = $user->name.' Added Successfully';
        $log->save();

        if($request->business == 1){
            return redirect()->back();
        }else{
            if($insert){
                return redirect()->route('admin.clients');
            }else{
                Session::flash('alert','Something Went Wrong.');
                return redirect()->route('admin.add.client');
            }
        }
    }
    public function editClient(Request $request, $id = NULL){
        $page = 'Clients';
        $icon = 'clients.png';
        $client = User::where('id',$id)->first();
        return view('admin.clients.edit_client',compact('page','icon','client'));
    }

    public function viewClient(Request $request, $id = NULL){
        $page = 'Clients';
        $icon = 'clients.png';
        $client = User::where('id',$id)->first();
        return view('admin.clients.view_client',compact('page','icon','client'));
    }

    public function updateClient(Request $req){
        $req->validate([
            'name'                => 'required',
            'email'               => 'required',
            'phone'               => 'required|unique:users,phone,' . $req->user_id,
        ]);
        if($req->status == "on"){
            $status = 1;
        }else{
            $status = 0;
        }
        $user = User::where('id',$req->user_id)->first();
        $user->name     = $req->name;
        $user->phone    = $req->phone;
        $user->email    = $req->email;
        if($req->has('password')){
            $user->password = Hash::make($req->password);
        }
        $user->status   = $status;
        $insert         = $user->save();

        $admin = User::where('id',Auth::user()->id)->first();
        $notificationData = [
            'type' => 'message',
            'title' => 'Client Updated - ',
            'text' => 'Name: '.$req->name,
            'url' => route('admin.view.client',$user->id),
        ];
  
        Notification::send($admin, new OffersNotification($notificationData));
        $log = new Log();
        $log->user_id   = Auth::user()->name;
        $log->module    = 'Client';
        $log->log       = $user->name.' Updated Successfully';
        $log->save();
        return redirect()->route('admin.clients');
    }
    public function deleteClient($id){
        $user = User::where('id',$id)->first();
        $user->delete();

        $admin = User::where('id',Auth::user()->id)->first();
        $notificationData = [
            'type' => 'message',
            'title' => 'Client Deleted - ',
            'text' => 'Name: '.$user->name,
            'url' => route('admin.clients'),
        ];
  
        Notification::send($admin, new OffersNotification($notificationData));

        $log = new Log();
        $log->user_id   = Auth::user()->name;
        $log->module    = 'Client';
        $log->log       = $user->name.' Deleted Successfully';
        $log->save();
        return 1;
    }
}
