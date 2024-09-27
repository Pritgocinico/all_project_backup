<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Hash;
use Session;
use App\Models\RoleUser;
use App\Models\User;
use App\Models\Company;
use App\Models\Log;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CompanyController extends Controller
{
    public function __construct() {
        $setting=Setting::first();
        $user = User::first();
        view()->share('setting', $setting);
    }
    public function companies(){
        $companies = Company::orderBy('id','Desc')->get();
        $page  = 'Insurance Company';
        $icon  = 'company.png';
        // if(Auth::user()->role == 1){
            return view('admin.companies.companies',compact('companies','page','icon'));
        // }else{
        //     return redirect()->route('login');
        // }
    }
    public function addCompany(Request $request){
        $page = 'Add Insuarance Company';
        $icon = 'company.png';
        // if(Auth::user()->role == 1){
            return view('admin.companies.add_company',compact('page','icon'));
        // }else{
        //     return redirect()->route('login');
        // }
    }
    public function addCompanyData(Request $req){
        $req->validate([
            'company_name'                => 'required|unique:companies,name',
        ]);

        $company = new Company();
        $company->name         = $req->company_name;
        $company->status       = 1;
        $company->save();

        $user1 = User::where('id',Auth::user()->id)->first();
        $log = new Log();
        $log->user_id   = Auth::user()->name;
        $log->module    = 'Company';
        $log->log       = 'Company ('.$company->name .') Created';
        $log->save();
        // if(Auth::user()->role == 1){
            return redirect()->route('admin.companies')->with('success', 'Company Added Successfully.');
        // }else{
        //     return redirect()->route('login');
        // }
    }
    public function editCompany(Request $request, $id = NULL){
        $page = 'Edit Insuarance Company';
        $icon = 'company.png';
        $company = Company::where('id',$id)->first();
        // if(Auth::user()->role == 1){
            return view('admin.companies.edit_company',compact('company','page','icon'));
        // }else{
        //     return redirect()->route('login');
        // }
    }
    public function updateCompany(Request $req){
        $req->validate([
            'company_name'                => 'required|unique:companies,name,'.$req->id,
        ]);
        if($req->status == "on"){
            $status = 1;
        }else{
            $status = 0;
        }
        $company = Company::where('id',$req->id)->first();
        $company->name         = $req->company_name;
        $company->status       = $status;
        $company->save();

        $user1 = User::where('id',Auth::user()->id)->first();
        $log = new Log();
        $log->user_id   = Auth::user()->name;
        $log->module    = 'Company';
        $log->log       = 'Company ('.$company->name .') Created';
        $log->save();
        // if(Auth::user()->role == 1){
            return redirect()->route('admin.companies')->with('success', 'Company Updated Successfully.');
        // }else{
        //     return redirect()->route('login');
        // }
    }
    public function deleteCompany($id){
        $company = Company::where('id',$id)->first();
        $user1 = User::where('id',Auth::user()->id)->first();
        $log = new Log();
        $log->user_id   = Auth::user()->id;
        $log->module    = 'Company';
        $log->log       = 'Company ('.$company->name .') Deleted';
        $log->save();
        $company->delete();
        return 1;
    }
}
