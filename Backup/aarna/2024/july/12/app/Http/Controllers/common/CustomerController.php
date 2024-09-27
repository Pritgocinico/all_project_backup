<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Hash;
use Session;
use App\Models\RoleUser;
use App\Models\User;
use App\Models\Customer;
use App\Models\Log;
use App\Models\Setting;
use App\Models\UserPermission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class CustomerController extends Controller
{
    public function __construct() {
        $setting=Setting::first();
        $user = User::first();
        view()->share('setting', $setting);
    }
    public function customers(Request $request){
        if($request->has('search')){
            $query = Customer::orderBy('id','DESC');
            if($request->has('name') && $request->name != ''){
                $query->where('name','LIKE','%'.$request->name.'%');
            }
            if($request->has('email') && $request->email != ''){
                $query->where('email',$request->email);
            }
            if($request->has('phone') && $request->phone != ''){
                $query->where('phone',$request->phone);
            }
            if($request->has('status') && $request->status != ''){
                if($request->status == 'active' || $request->status == 'Active' || $request->status == 'ACTIVE'){
                    $status = 1;
                }elseif($request->status == 'deactive' || $request->status == 'Deactive' || $request->status == 'DeActive' || $request->status == 'DEACTIVE'){
                    $status = 0;
                }else{
                    $status = 1;
                }
                $query->where('status',$status);
            }
            $customers = $query->paginate(10);
        }else{
            $customers = Customer::orderBy('id','Desc')->paginate(10);
        }
        $page  = 'Customers';
        $icon  = 'customer.png';
        // if(Auth::user()->role == 1){
            return view('admin.customers.customers',compact('customers','page','icon'));
        // }else{
        //     return redirect()->route('login');
        // }
    }

    public function getCustomerData(Request $request)
    {
        $permissions = UserPermission::where('user_id',Auth::user()->id)->get();
        if ($request->ajax()) {
            $data = Customer::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $actionBtn = '<ul class="action">';
                    if(Auth::user()->id == 1) {
                        $actionBtn .= '<li class="edit"><a href="' . route('admin.edit_customer', $row->id) . '" ><i class="icon-pencil-alt"></i></i></a></li>';
                        $actionBtn .= '<li class="delete"><a href="javascript:void(0);" data-id="' . $row->id . '" class="delete-btn"><i class="icon-trash"></i></a></li>';
                    } else {
                             $actionBtn .= '<li class="edit"><a href="' . route('admin.edit_customer', $row->id) . '" ><i class="icon-pencil-alt"></i></a></li>';
                    }
                    $actionBtn .= '</ul>';
                    return $actionBtn;
                })
                ->addColumn('status', function($row){
                    $status = $row->status == 1 ? 'Active' : 'Inactive';
                    return '<span class="badge badge-' . ($row->status == 1 ? 'success' : 'danger') . '">' . $status . '</span>';
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
    }

    public function addCustomer(Request $request){
        $page = 'Add Customer';
        $icon = 'customer.png';
        // if(Auth::user()->role == 1){
            return view('admin.customers.add_customer',compact('page','icon'));
        // }else{
        //     return redirect()->route('login');
        // }
    }
    public function addCustomerData(Request $req){
        if($req->has('modal')){
            $customer = new Customer();
            $customer->name         = $req->name;
            $customer->email        = $req->email;
            $customer->phone        = $req->phone;
            $customer->status        = 1;
            $customer->save();

            $user1 = User::where('id',Auth::user()->id)->first();
            $log = new Log();
            $log->user_id   = Auth::user()->name;
            $log->module    = 'Customer';
            $log->log       = 'Customer ('.$customer->name .') Created';
            $log->save();
            return response()->json($customer);
        }else{
            $req->validate([
                'name'                => 'required',
                // 'email'                => 'required|unique:customers,email',
                'phone'                => 'required',
            ]);
            $customer = new Customer();
            $customer->name         = $req->name;
            $customer->email        = $req->email;
            $customer->phone        = $req->phone;
            $customer->status        = 1;
            $customer->save();

            $user1 = User::where('id',Auth::user()->id)->first();
            $log = new Log();
            $log->user_id   = Auth::user()->name;
            $log->module    = 'Customer';
            $log->log       = 'Customer ('.$customer->name .') Created';
            $log->save();
            // if(Auth::user()->role == 1){
                return redirect()->route('admin.customers')->with('success', 'Customer Added Successfully.');
            // }else{
            //     return redirect()->route('login');
            // }
        }
        
    }
    public function editCustomer(Request $request, $id = NULL){
        $page = 'Customers';
        $icon = 'customer.png';
        $customer = Customer::where('id',$id)->first();
        // if(Auth::user()->role == 1){
            return view('admin.customers.edit_customer',compact('customer','page','icon'));
        // }else{
        //     return redirect()->route('login');
        // }
    }
    public function updateCustomer(Request $req){
        $req->validate([
            'name'                => 'required',
            // 'email'                => 'required|unique:customers,email,'.$req->id,
            'phone'                => 'required',
        ]);
        if($req->status == "on"){
            $status = 1;
        }else{
            $status = 0;
        }
        $customer = Customer::where('id',$req->id)->first();
        $customer->name         = $req->name;
        $customer->email        = $req->email;
        $customer->phone        = $req->phone;
        $customer->status        = 1;
        $customer->save();

        $user1 = User::where('id',Auth::user()->id)->first();
        $log = new Log();
        $log->user_id   = Auth::user()->name;
        $log->module    = 'Customer';
        $log->log       = 'Customer ('.$customer->name .') Updated';
        $log->save();
        // if(Auth::user()->role == 1){
            return redirect()->route('admin.customers')->with('success', 'Customer Updated Successfully.');
        // }else{
        //     return redirect()->route('login');
        // }
    }
    public function deleteCustomer($id){
        $customer = Customer::where('id',$id)->first();
        $user1 = User::where('id',Auth::user()->id)->first();
        $log = new Log();
        $log->user_id   = Auth::user()->id;
        $log->module    = 'Customer';
        $log->log       = 'Customer ('.$customer->name .') Deleted';
        $log->save();
        $customer->delete();
        return 1;
    }
}
