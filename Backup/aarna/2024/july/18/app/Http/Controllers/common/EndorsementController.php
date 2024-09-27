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
use App\Models\Endorsement;
use App\Models\Policy;
use App\Models\EndorsementAttachment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use URL;

class EndorsementController extends Controller
{
    public function __construct() {
        $setting=Setting::first();
        $user = User::first();
        view()->share('setting', $setting);
    }
    public function endorsement($id = NULL){
        $endorsements = Endorsement::orderBy('id','Desc')->where('policy_id',$id)->get();
        $page  = 'Endorsements';
        $icon  = 'endorsement.png';
        $policy = Policy::where('id',$id)->first();
        // if(Auth::user()->role == 1){
            return view('admin.policy.endorsement.endorsement',compact('endorsements','page','icon','id','policy'));
        // }else{
        //     return redirect()->route('login');
        // }
    }
    public function addEndorsement($id = NULL){
        $page  = 'Endorsements';
        $icon  = 'endorsement.png';
        // if(Auth::user()->role == 1){
            return view('admin.policy.endorsement.add_endorsement',compact('page','icon','id'));
        // }else{
        //     return redirect()->route('login');
        // }
    }
    public function addEndorsementData(Request $request){
        if($request->has('payment_type') && $request->payment_type == 3){
            $request->validate([
                'details'               => 'required',
                'documents'             => 'required',
                'transaction_no'        => 'required',
            ]);
        }elseif($request->has('payment_type') && $request->payment_type == 2){
            $request->validate([
                'details'               => 'required',
                'documents'             => 'required',
                'cheque_date'           => 'required',
                'bank_name'             => 'required',
            ]);
        }else{
            $request->validate([
                'details'               => 'required',
                'documents'             => 'required',
            ]);
        }

        $endorsement = new Endorsement();
        $endorsement->policy_id             = $request->policy_id;
        $endorsement->details               = $request->details;
        $endorsement->supporting_documents  = $request->documents;
        $endorsement->payment_type          = $request->payment_type;
        $endorsement->cheque_no             = $request->cheque_no;
        $endorsement->cheque_date           = $request->cheque_date;
        $endorsement->bank_name             = $request->bank_name;
        $endorsement->save();

        if($request->has('file')){
            foreach($request->file as $file){
                if($file && $file != null){
                    $fileName = $file->getClientOriginalName();
                    $image_file = $file;
                    $destinationPath1 = 'public/endorsement_attachment/';
                    $rand1=rand(1,100);
                    $docImage1 = date('YmdHis').$rand1. "." . $image_file->getClientOriginalExtension();
                    $image_file->move($destinationPath1, $docImage1);
                    $img_attachment=$docImage1;
                }else{
                    $fileName = "";
                    $img_attachment='';
                }
                $attachment = new EndorsementAttachment();
                $attachment->endorsement_id     = $endorsement->id;
                $attachment->policy_id          = $request->policy_id;
                $attachment->file_name          = $fileName;
                $attachment->file               = $img_attachment;
                $attachment->save();
            }
        }
        // if(Auth::user()->role == 1){
            return redirect()->route('admin.endorsement',$request->policy_id)->with('success', 'Endorsement Added Successfully.');;
        // }else{
        //     return redirect()->route('login');
        // }
    }
    public function deleteEndorsement($id){
        $endorsement = Endorsement::where('id',$id)->first();
        $user1 = User::where('id',Auth::user()->id)->first();
        $log = new Log();
        $log->user_id   = Auth::user()->id;
        $log->module    = 'Endorsement';
        $log->log       = 'Endorsement has been Deleted';
        $log->save();
        $endorsement->delete();
        return 1;
    }
    public function getEndorsementAttachment(Request $request, $id = NULL){
        $attachments = EndorsementAttachment::where('endorsement_id',$id)->get();
        foreach ($attachments as $attachment) {
            $mock['name'] = $attachment->file;
            $dirUrl = URL::asset('endorsement_attachment/');
            $mock['url'] = $dirUrl.'/'.$attachment->file;
            $mocks[] = $mock;
        }
        $attachments = json_encode($mocks);
        return response()->json($mocks, 200);
    }
    public function editEndorsement(Request $request,$id = NULL){
        $page  = 'Endorsements';
        $icon  = 'endorsement.png';
        $endorsement = Endorsement::where('id',$id)->first();
        $mocks = [];
        $policy = Policy::where('id',$endorsement->policy_id)->first();
        // if(Auth::user()->role == 1){
            return view('admin.policy.endorsement.edit_endorsement',compact('page','icon','endorsement','policy'));
        // }else{
        //     return redirect()->route('login');
        // }
    }
    public function editEndorsementData(Request $request){
        if($request->has('payment_type') && $request->payment_type == 3){
            $request->validate([
                'details'               => 'required',
                'documents'             => 'required',
                'transaction_no'        => 'required',
            ]);
        }elseif($request->has('payment_type') && $request->payment_type == 2){
            $request->validate([
                'details'               => 'required',
                'documents'             => 'required',
                'cheque_date'           => 'required',
                'bank_name'             => 'required',
            ]);
        }else{
            $request->validate([
                'details'               => 'required',
                'documents'             => 'required',
            ]);
        }

        $endorsement = Endorsement::where('id',$request->id)->first();
        $endorsement->policy_id             = $request->policy_id;
        $endorsement->details               = $request->details;
        $endorsement->supporting_documents  = $request->documents;
        $endorsement->payment_type          = $request->payment_type;
        $endorsement->cheque_no             = $request->cheque_no;
        $endorsement->cheque_date           = $request->cheque_date;
        $endorsement->bank_name             = $request->bank_name;
        $endorsement->save();

        if($request->has('file')){
            foreach($request->file as $file){
                if($file && $file != null){
                    $fileName = $file->getClientOriginalName();
                    $image_file = $file;
                    $destinationPath1 = 'public/endorsement_attachment/';
                    $rand1=rand(1,100);
                    $docImage1 = date('YmdHis').$rand1. "." . $image_file->getClientOriginalExtension();
                    $image_file->move($destinationPath1, $docImage1);
                    $img_attachment=$docImage1;
                }else{
                    $fileName = "";
                    $img_attachment='';
                }
                $attachment = new EndorsementAttachment();
                $attachment->endorsement_id     = $endorsement->id;
                $attachment->file_name     = $fileName;
                $attachment->policy_id          = $request->policy_id;
                $attachment->file               = $img_attachment;
                $attachment->save();
            }
        }
        // if(Auth::user()->role == 1){
            return redirect()->route('admin.endorsement',$request->policy_id)->with('success', 'Endorsement Updated Successfully.');;
        // }else{
        //     return redirect()->route('login');
        // }
    }
}
