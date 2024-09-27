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
use App\Models\Claim;
use App\Models\Policy;
use App\Models\ClaimAttachment;
use App\Models\ClaimRemark;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use URL;
use Carbon\Carbon;
class ClaimController extends Controller
{
    public function __construct() {
        $setting=Setting::first();
        $user = User::first();
        view()->share('setting', $setting);
    }
    public function claims($id = NULL){
        $claims = Claim::with('policy.customers')->orderBy('id','Desc')->where('policy_id',$id)->get();
        $page  = 'Claims';
        $icon  = 'insurance.png';
        $policy = Policy::where('id',$id)->first();
        // if(Auth::user()->role == 1){
            return view('admin.policy.claim.claims',compact('claims','page','icon','id','policy'));
        // }else{
        //     return redirect()->route('login');
        // }
    }
    public function addClaim($id = NULL){
        $page  = 'Add Claims';
        $icon  = 'insurance.png';
        // if(Auth::user()->role == 1){
            return view('admin.policy.claim.add_claim',compact('page','icon','id'));
        // }else{
        //     return redirect()->route('login');
        // }
    }
    public function addClaimData(Request $request){
        if($request->has('payment_type') && $request->payment_type == 3){
            if($request->has('claim_status') && $request->claim_status != 1){
                $request->validate([
                    'claim_date'            => 'required',
                    'claim_no'              => 'required',
                    'contact_person'        => 'required',
                    'contact_person_no'     => 'required',
                    'transaction_no'        => 'required',
                    'status_date'           => 'required',
                    'claim_type'            => 'required|not_in:0'
                ]);
            }else{
                $request->validate([
                    'claim_date'            => 'required',
                    'claim_no'              => 'required',
                    'contact_person'        => 'required',
                    'contact_person_no'     => 'required',
                    'transaction_no'        => 'required',
                    'claim_type'            => 'required|not_in:0'

                ]);
            }
        }elseif($request->has('payment_type') && $request->payment_type == 2){
            if($request->has('claim_status') && $request->claim_status != 1){
                $request->validate([
                    'claim_date'            => 'required',
                    'claim_no'              => 'required',
                    'contact_person'        => 'required',
                    'contact_person_no'     => 'required',
                    'cheque_date'           => 'required',
                    'bank_name'             => 'required',
                    'status_date'           => 'required',
                    'claim_type'            => 'required|not_in:0'
                ]);
            }else{
                $request->validate([
                    'claim_date'            => 'required',
                    'claim_no'              => 'required',
                    'contact_person'        => 'required',
                    'contact_person_no'     => 'required',
                    'cheque_date'           => 'required',
                    'bank_name'             => 'required',
                    'claim_type'            => 'required|not_in:0'

                ]);
            }
        }else{
            if($request->has('claim_status') && $request->claim_status != 1){
                $request->validate([
                    'claim_date'            => 'required',
                    'claim_no'              => 'required',
                    'contact_person'        => 'required',
                    'contact_person_no'     => 'required',
                    'status_date'           => 'required',
                    'claim_type'            => 'required|not_in:0'
                ]);
            }else{
                $request->validate([
                    'claim_date'            => 'required',
                    'claim_no'              => 'required',
                    'contact_person'        => 'required',
                    'contact_person_no'     => 'required',
                    'claim_type'            => 'required|not_in:0'
                ]);
            }
        }
        $claim = new Claim();
        $claim->policy_id           = $request->policy_id;
        $claim->claim_date          = $request->claim_date;
        $claim->claim_no            = $request->claim_no;
        $claim->contact_person      = $request->contact_person;
        $claim->contact_person_no   = $request->contact_person_no;
        $claim->surveyar_name       = $request->surveyar_name;
        $claim->surveyar_no         = $request->surveyar_no;
        $claim->surveyar_email      = $request->surveyar_email;
        $claim->repairing_location  = $request->repairing_location;
        $claim->claim_status        = $request->claim_status;
        $claim->claim_type          = $request->claim_type;
        $claim->status_text         = $request->status_text;
        $claim->status_date         = $request->status_date;
        $claim->payment_type        = $request->payment_type;
        $claim->cheque_no           = $request->cheque_no;
        $claim->cheque_date         = $request->cheque_date;
        $claim->bank_name           = $request->bank_name;
        $claim->transaction_no      = $request->transaction_no;
        $claim->remarks             = $request->remarks;
        $claim->save();
        if($request->has('remark')){
            $remark = new ClaimRemark();
            $remark->claim_id       = $claim->id;
            $remark->remark         = $request->remark;
            $remark->remark_date    = Carbon::now();
            $remark->created_by     = Auth::user()->id;
            $remark->save();
        }
        if($request->has('file')){
            foreach($request->file as $file){
                if($file && $file != null){
                    $fileName = $file->getClientOriginalName();
                    $image_file = $file;
                    $destinationPath1 = 'public/claim_attachment/';
                    $rand1=rand(1,100);
                    $docImage1 = date('YmdHis').$rand1. "." . $image_file->getClientOriginalExtension();
                    $image_file->move($destinationPath1, $docImage1);
                    $img_attachment=$docImage1;
                }else{
                    $fileName = "";
                    $img_attachment='';
                }
                $attachment = new ClaimAttachment();
                $attachment->claim_id   = $claim->id;
                $attachment->file_name = $fileName;
                $attachment->policy_id  = $request->policy_id;
                $attachment->file       = $img_attachment;
                $attachment->save();
            }
        }
        // if(Auth::user()->role == 1){
            return redirect()->route('admin.claims',$request->policy_id)->with('success', 'Claim Added Successfully.');
        // }else{
        //     return redirect()->route('login');
        // }
    }
    public function getClaimAttachment(Request $request, $id = NULL){
        $attachments = ClaimAttachment::where('claim_id',$id)->get();
        foreach ($attachments as $attachment) {
            $mock['name'] = $attachment->file_name?$attachment->file_name:$attachment->file;
            $dirUrl = URL::asset('claim_attachment/');
            $mock['url'] = $dirUrl.'/'.$attachment->file;
            $mocks[] = $mock;
        }
        $attachments = json_encode($mocks);
        return response()->json($mocks, 200);
    }
    public function editClaim(Request $request,$id = NULL){
        $page  = 'Edit Claims';
        $icon  = 'insurance.png';
        $claim = Claim::where('id',$id)->first();
        $mocks = [];
        $policy = Policy::where('id',$claim->policy_id)->first();
        // if(Auth::user()->role == 1){
            return view('admin.policy.claim.edit_claim',compact('page','icon','claim','policy'));
        // }else{
        //     return redirect()->route('login');
        // }
    }
    public function editClaimData(Request $request){
        if($request->has('payment_type') && $request->payment_type == 3){
            if($request->has('claim_status') && $request->claim_status != 1){
                $request->validate([
                    'claim_date'            => 'required',
                    'claim_no'              => 'required',
                    'contact_person'        => 'required',
                    'contact_person_no'     => 'required',
                    'transaction_no'        => 'required',
                    'status_date'           => 'required',
                    'claim_type'            => 'required|not_in:0'
                ]);
            }else{
                $request->validate([
                    'claim_date'            => 'required',
                    'claim_no'              => 'required',
                    'contact_person'        => 'required',
                    'contact_person_no'     => 'required',
                    'transaction_no'        => 'required',
                    'claim_type'            => 'required|not_in:0'

                ]);
            }
        }elseif($request->has('payment_type') && $request->payment_type == 2){
            if($request->has('claim_status') && $request->claim_status != 1){
                $request->validate([
                    'claim_date'            => 'required',
                    'claim_no'              => 'required',
                    'contact_person'        => 'required',
                    'contact_person_no'     => 'required',
                    'cheque_date'           => 'required',
                    'bank_name'             => 'required',
                    'status_date'           => 'required',
                    'claim_type'            => 'required|not_in:0'
                ]);
            }else{
                $request->validate([
                    'claim_date'            => 'required',
                    'claim_no'              => 'required',
                    'contact_person'        => 'required',
                    'contact_person_no'     => 'required',
                    'cheque_date'           => 'required',
                    'bank_name'             => 'required',
                    'claim_type'            => 'required|not_in:0'

                ]);
            }
        }else{
            if($request->has('claim_status') && $request->claim_status != 1){
                $request->validate([
                    'claim_date'            => 'required',
                    'claim_no'              => 'required',
                    'contact_person'        => 'required',
                    'contact_person_no'     => 'required',
                    'status_date'           => 'required',
                    'claim_type'            => 'required|not_in:0'
                ]);
            }else{
                $request->validate([
                    'claim_date'            => 'required',
                    'claim_no'              => 'required',
                    'contact_person'        => 'required',
                    'contact_person_no'     => 'required',
                    'claim_type'            => 'required|not_in:0'
                ]);
            }
        }
        $claim = Claim::where('id',$request->id)->first();
        $claim->policy_id           = $request->policy_id;
        $claim->claim_date          = $request->claim_date;
        $claim->claim_no            = $request->claim_no;
        $claim->contact_person      = $request->contact_person;
        $claim->contact_person_no   = $request->contact_person_no;
        $claim->surveyar_name       = $request->surveyar_name;
        $claim->surveyar_no         = $request->surveyar_no;
        $claim->surveyar_email      = $request->surveyar_email;
        $claim->repairing_location  = $request->repairing_location;
        $claim->claim_status        = $request->claim_status;
        $claim->claim_type          = $request->claim_type;
        $claim->status_text         = $request->status_text;
        $claim->status_date         = $request->status_date;
        $claim->payment_type        = $request->payment_type;
        $claim->cheque_no           = $request->cheque_no;
        $claim->cheque_date         = $request->cheque_date;
        $claim->bank_name           = $request->bank_name;
        // $claim->remarks             = $request->remarks;
        $claim->save();

        if($request->has('file')){
            foreach($request->file as $file){
                if($file && $file != null){
                    $fileName = $file->getClientOriginalName();
                    $image_file = $file;
                    $destinationPath1 = 'public/claim_attachment/';
                    $rand1=rand(1,100);
                    $docImage1 = date('YmdHis').$rand1. "." . $image_file->getClientOriginalExtension();
                    $image_file->move($destinationPath1, $docImage1);
                    $img_attachment=$docImage1;
                }else{
                    $fileName = "";
                    $img_attachment='';
                }
                $attachment = new ClaimAttachment();
                $attachment->claim_id   = $claim->id;
                $attachment->policy_id  = $request->policy_id;
                $attachment->file_name  = $fileName;
                $attachment->file       = $img_attachment;
                $attachment->save();
            }
        }
        // if(Auth::user()->role == 1){
            return redirect()->route('admin.claims',$request->policy_id)->with('success', 'Claim Updated Successfully.');
        // }else{
        //     return redirect()->route('login');
        // }
    }
    public function deleteClaim($id){
        $claim = Claim::where('id',$id)->first();
        $user1 = User::where('id',Auth::user()->id)->first();
        $log = new Log();
        $log->user_id   = Auth::user()->id;
        $log->module    = 'Claim';
        $log->log       = 'Claim has been Deleted';
        $log->save();
        $claim->delete();
        return 1;
    }
    public function claimRemarks(Request $request, $id = NULL){
        $page       = 'Claim Remarks';
        $icon       = 'insurance.png';
        $claim      = Claim::where('id',$id)->first();
        $mocks      = [];
        $policy     = Policy::where('id',$claim->policy_id)->first();
        $remarks    = ClaimRemark::where('claim_id',$id)->get();
        $users      = User::all();
        // if(Auth::user()->role == 1){
            return view('admin.policy.claim.remarks',compact('page','icon','claim','policy','remarks','users'));
        // }else{
        //     return redirect()->route('login');
        // }
    }
    public function addRemarkData(Request $request){
        $claim_id = $request->id;
        $remark_data = $request->remark;
        $remark_date = $request->date;

        $remark = new ClaimRemark();
        $remark->claim_id = $claim_id;
        $remark->remark = $remark_data;
        $remark->remark_date = $remark_date;
        $remark->created_by = Auth::user()->id;
        $remark->save();

        return 1;
    }
    public function viewClaim(Request $request, $id = NULL){
        $page  = 'View Claims';
        $icon  = 'insurance.png';
        $claim = Claim::where('id',$id)->first();
        $mocks = [];
        $policy = Policy::where('id',$claim->policy_id)->first();
        $documents = ClaimAttachment::where('claim_id',$claim->id)->get();
        // if(Auth::user()->role == 1){
            return view('admin.policy.claim.view_claim',compact('page','icon','claim','policy','documents'));
        // }else{
        //     return redirect()->route('login');
        // }
    }
    public function claimList(){
        $claims = Claim::orderBy('id', 'Desc')->with('policy')->get();
        $page  = 'Claims';
        $icon  = 'insurance.png';
        return view('admin.policy.claim.claims_list',compact('claims','page','icon'));
    }
}
