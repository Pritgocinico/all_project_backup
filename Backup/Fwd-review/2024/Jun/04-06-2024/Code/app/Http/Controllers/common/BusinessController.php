<?php

namespace App\Http\Controllers\common;

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
use App\Models\Plan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Notification;
use App\Notifications\OffersNotification;

class BusinessController extends Controller
{
    public function __construct() {
        $setting=Setting::first();
        $user = User::first();
        view()->share('setting', $setting);
    }
    public function business(Request $request, $id = NULL){
        $page = 'Business';
        $icon = 'add-user.png';
        $currDate = Carbon::now();
        $currentDate = $currDate->format('Y-m-d');
        if(isset($id)){
            $businesses = Business::where('active',1)->where('client_id',$id)->with('client')->get();
        }else{
            $businesses = Business::where('active',1)->with('client')->get();
        }
        return view('admin.business.businesses',compact('page','icon','businesses','id', 'currentDate'));
    }
    public function addBusiness(Request $request){
        $page = 'Business';
        $icon = 'add-user.png';
        $clients = User::where('role',2)->get();
        $planList = Plan::where('status',1)->get();
        return view('admin.business.add_business',compact('planList','page','icon','clients'));
    }
    public function addBusinessData(Request $request){
        $request->validate([
            'name'          => 'required',
            'client'        => 'required|not_in:0',
            'shortname'     => 'required|unique:businesses,shortname',
            'place_id'      => 'required|unique:businesses,place_id',
            'api_key'       => 'required',
            'plan'       => 'required',
            'payment_option'       => 'required',
        ]);
        $currentDate = Carbon::now();
        $planDetail = Plan::where('id',$request->plan)->first();
        if ($planDetail->plan_period_type == "years") {
            $date = $currentDate->addYear()->format('Y-m-d');
        } else if ($planDetail->plan_period_type == "months") {
            $date = $currentDate->addMonth()->format('Y-m-d');
        } else {
            $date = $currentDate->addDay()->format('Y-m-d');
        }
        $business = new Business();
        $business->name         = $request->name;
        $business->client_id    = $request->client;
        $business->shortname    = $request->shortname;
        $business->place_id     = $request->place_id;
        $business->api_key      = $request->api_key;
        $business->plan_id      = $request->plan;
        $business->payment_option      = $request->payment_option;
        $business->sub_end_date      = $date;
        $business->status       = 1;
        $business->active       = 1;
        $insert                 = $business->save();

        $admin = User::where('id',Auth::user()->id)->first();
        $notificationData = [
            'type' => 'message',
            'title' => 'Business Added - ',
            'text' => 'Name: '.$request->name,
            'url' => route('admin.business'),
        ];
  
        Notification::send($admin, new OffersNotification($notificationData));

        $log = new Log();
        $log->user_id   = Auth::user()->name;
        $log->module    = 'Business';
        $log->log       = $business->name.' Created Successfully';
        $log->save();

        if($insert){
            Session::flash('success','Business Created Successfully.');
            return redirect()->route('admin.business');
        }else{
            Session::flash('error','Something Went Wrong.');
            return redirect()->route('admin.add.business');
        }
    }
    public function editBusiness(Request $request, $id = NULL){
        $page = 'Business';
        $icon = 'add-user.png';
        $clients = User::where('role',2)->get();
        $business = Business::where('id',$id)->first();
        if(Auth()->user()->role == 2){
            return view('client.business.edit_business',compact('page','icon','clients','business'));
        }
        return view('admin.business.edit_business',compact('page','icon','clients','business'));
    }
    public function updateBusiness(Request $request){
        $request->validate([
            'name'          => 'required',
            'client'        => 'required|not_in:0',
            'shortname'     => 'required',
            'place_id'      => 'required',
            'api_key'       => 'required',
        ]);

        $business = Business::where('id',$request->id)->first();
        $business->name         = $request->name;
        $business->client_id    = $request->client;
        $business->shortname    = $request->shortname;
        $business->place_id     = $request->place_id;
        $business->api_key      = $request->api_key;
        $business->status       = 1;
        $business->active       = 1;
        $insert                 = $business->save();

        $admin = User::where('id',Auth::user()->id)->first();
        $notificationData = [
            'type' => 'message',
            'title' => 'Business Updated - ',
            'text' => 'Name: '.$request->name,
            'url' => route('admin.business'),
        ];
  
        Notification::send($admin, new OffersNotification($notificationData));
        $log = new Log();
        $log->user_id   = Auth::user()->name;
        $log->module    = 'Business';
        $log->log       = $business->name.' Updated Successfully';
        $log->save();

        if($insert){
            Session::flash('success','Business Updated Successfully.');
            if(Auth()->user() !== null && Auth()->user()->role == 2){
                return redirect()->route('client.business');
            }
            return redirect()->route('admin.business');
        }else{
            Session::flash('error','Something Went Wrong.');
            if(Auth()->user() !== null && Auth()->user()->role == 2){
                return redirect()->route('client.edit.business',$request->id);
            }
            return redirect()->route('admin.edit.business',$request->id);
        }
    }
    public function deleteBusiness($id){
        $user = Business::where('id',$id)->first();
        $user->delete();

        $admin = User::where('id',Auth::user()->id)->first();
        $notificationData = [
            'type' => 'message',
            'title' => 'Business Deleted - ',
            'text' => 'Name: '.$user->name,
            'url' => route('admin.business'),
        ];
  
        Notification::send($admin, new OffersNotification($notificationData));
        $log = new Log();
        $log->user_id   = Auth::user()->name;
        $log->module    = 'Business';
        $log->log       = $user->name.' Deleted Successfully';
        $log->save();
        if($user){
            return response()->json(['data'=>'','message'=> 'Business Deleted Successfully','status'=>1],200);
        }
        return response()->json(['data'=>'','message'=> 'Something Went To Wrong.','status'=>1],500);
    }

    public function businessRequest(Request $request,$id = NULL){
        $businessList = Business::where('delete_request',1)->get();
        $page = 'Stop Subscription Request';
        $icon = 'add-user.png';
        return view('admin.business.business_request',compact('page','id','icon','businessList'));
    }

    public function viewBusiness(Request $request,$id = NULL){
        $page = 'Business';
        $icon = 'add-user.png';
        $clients = User::where('role',2)->get();
        $business = Business::where('id',$id)->first();
        return view('admin.business.edit_business',compact('page','icon','clients','business'));
    }

    public function businessDetail($id = NULL){
        $page  = "Business Detail";
        $icon = 'add-user.png';
        $business = Business::with('client','paymentDetail')->where('id',$id)->first();
        if(!blank($business)){
            $paymentDetail = Payment::where('business_id',$business->id)->get();
            $client = User::where('id',$business->client_id)->first();
            return view('admin.business.view_business',compact('page','icon','business','client','paymentDetail'));
        }
        abort(404);
    }

}
