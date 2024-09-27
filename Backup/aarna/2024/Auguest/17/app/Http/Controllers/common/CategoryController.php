<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Hash;
use Session;
use App\Models\RoleUser;
use App\Models\User;
use App\Models\Category;
use App\Models\Log;
use App\Models\Setting;
use App\Models\Parameter;
use App\Models\PolicyParameter;
use App\Models\CovernoteParameter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function __construct() {
        $setting=Setting::first();
        $user = User::first();
        view()->share('setting', $setting);
    }
    public function categories(){
        $categories = Category::orderBy('id','Desc')->get();
        $parent_categories = Category::where('parent','==',0)->orderBy('id','Desc')->get();
        $page  = 'Categories';
        $icon  = 'category.png';
        // if(Auth::user()->role == 1){
            return view('admin.categories.categories',compact('categories','parent_categories','page','icon'));
        // }else{
        //     return redirect()->route('login');
        // }
    }
    public function addCategoryData(Request $req){
        $req->validate([
            'name'                => 'required|unique:categories,name',
        ]);

        $category = new Category();
        $category->name         = $req->name;
        $category->description  = $req->description;
        $category->created_by   = Auth::user()->id;
        $category->status       = 1;
        $category->save();

        $user1 = User::where('id',Auth::user()->id)->first();
        $log = new Log();
        $log->user_id   = Auth::user()->name;
        $log->module    = 'Category';
        $log->log       = 'Category ('.$category->name .') Created';
        $log->save();
        // if(Auth::user()->role == 1){
            return redirect()->route('admin.categories')->with('success', 'Category Added Successfully.');
        // }else{
        //     return redirect()->route('login');
        // }
    }
    public function editCategory($id){
        $category           = Category::where('id',$id)->first();
        $page               = 'Edit Category';
        $icon               = 'category.png';
        // if(Auth::user()->role == 1){
            return view('admin.categories.edit_category',compact('category','page','icon'));
        // }else{
        //     return redirect()->route('login');
        // }
    }
    public function updateCategory(Request $req){
        $req->validate([
            'name'                => 'required|unique:categories,name,'.$req->category_id,
        ]);
        if($req->status == "on"){
            $status = 1;
        }else{
            $status = 0;
        }
        $category = Category::where('id',$req->category_id)->first();
        $category->name         = $req->name;
        $category->description  = $req->description;
        $category->status       = $status;
        $category->save();

        $user1 = User::where('id',Auth::user()->id)->first();
        $log = new Log();
        $log->user_id   = Auth::user()->name;
        $log->module    = 'Category';
        $log->log       = 'Category ('.$category->name .') Updated';
        $log->save();
        // if(Auth::user()->role == 1){
            return redirect()->route('admin.categories')->with('success', 'Category Updated Successfully.');
        // }else{
        //     return redirect()->route('login');
        // }
    }
    public function deleteCategory($id){
        $category = Category::where('id',$id)->first();
        $user1 = User::where('id',Auth::user()->id)->first();
        $log = new Log();
        $log->user_id   = Auth::user()->id;
        $log->module    = 'Category';
        $log->log       = 'Category ('.$category->name .') Deleted';
        $log->save();
        $category->delete();
        $cat = Category::where('parent',$id)->get();
        if(count($cat)>0){
            Category::where('parent',$id)->delete();
        }
        return 1;
    }
    public function subCategories(){
        $categories = Category::orderBy('id','Desc')->where('parent','==',0)->get();
        $sub_categories = Category::where('parent','!=',0)->orderBy('id','Desc')->get();
        $page  = 'Sub Categories';
        $icon  = 'sub_category.png';
        // if(Auth::user()->role == 1){
            return view('admin.sub_categories.sub_categories',compact('categories','sub_categories','page','icon'));
        // }else{
        //     return redirect()->route('login');
        // }
    }
    public function addSubCategoryData(Request $req){
        $req->validate([
            'name'              => 'required|unique:categories,name',
            'parent'            => 'required|not_in:0',
            // 'renewable'         => 'accepted',
        ]);
        if($req->renewable == "on"){
            $renewable = 1;
        }else{
            $renewable = 0;
        }
        $category = new Category();
        $category->name         = $req->name;
        $category->parent       = $req->parent;
        $category->description  = $req->description;
        $category->renewable    = $renewable;
        $category->gst          = $req->gst;
        $category->created_by   = Auth::user()->id;
        $category->status       = 1;
        $category->save();

        $user1 = User::where('id',Auth::user()->id)->first();
        $log = new Log();
        $log->user_id   = Auth::user()->name;
        $log->module    = 'Sub Category';
        $log->log       = 'Sub Category ('.$category->name .') Created';
        $log->save();
        // if(Auth::user()->role == 1){
            return redirect()->route('admin.sub_categories')->with('success', 'Sub Category Added Successfully.');
        // }else{
        //     return redirect()->route('login');
        // }
    }
    public function editSubCategory($id){
        $categories = Category::orderBy('id','Desc')->where('parent','==',0)->get();
        $category           =  Category::where('id',$id)->first();
        $page               = 'Edit Sub Category';
        $icon               = 'sub_category.png';
        if(Auth::user()->role == 1){
            return view('admin.sub_categories.edit_sub_category',compact('categories','category','page','icon'));
        }else{
            return redirect()->route('login');
        }
    }
    public function updateSubCategory(Request $req){
        $req->validate([
            'name'                => 'required|unique:categories,name,'.$req->category_id,
            'parent'              => 'required|not_in:0',
        ]);
        if($req->status == "on"){
            $status = 1;
        }else{
            $status = 0;
        }
        if($req->renewable == "on"){
            $renewable = 1;
        }else{
            $renewable = 0;
        }
        $category = Category::where('id',$req->category_id)->first();
        $category->name         = $req->name;
        $category->parent       = $req->parent;
        $category->renewable    = $renewable;
        $category->gst          = $req->gst;
        $category->description  = $req->description;
        $category->status       = $status;
        $category->save();

        $user1 = User::where('id',Auth::user()->id)->first();
        $log = new Log();
        $log->user_id   = Auth::user()->name;
        $log->module    = 'Category';
        $log->log       = 'Category ('.$category->name .') Updated';
        $log->save();
        // if(Auth::user()->role == 1){
            return redirect()->route('admin.sub_categories')->with('success', 'Sub Category Updated Successfully.');
                    // }else{
        //     return redirect()->route('login');
        // }
    }
    public function deleteSubCategory($id){
        $category = Category::where('id',$id)->first();
        $user1 = User::where('id',Auth::user()->id)->first();
        $log = new Log();
        $log->user_id   = Auth::user()->id;
        $log->module    = 'Sub Category';
        $log->log       = 'Sub Category ('.$category->name .') Deleted';
        $log->save();
        $category->delete();
        return 1;
    }
    public function getCatSubcategory(Request $req){
        $id = $req->id;
        $categories = Category::where('parent',$id)->where('status',1)->get();
        $html = '';
        foreach($categories as $category)
        {
            $html .= '<option value="'.$category->id.'" data-gst="'.$category->gst.'">'.$category->name.'</option>';
        }
        return $html;
    }
    public function TpCalculationParameters($id){
        $category           = Category::where('id',$id)->first();
        $parameters         = Parameter::where('sub_category_id',$id)->get();
        $public_param = array();
        foreach($parameters as $parameter){
            if($parameter->type == 1){
                $public_param[] = $parameter;
            }
        }
        $private_param = array();
        foreach($parameters as $parameter){
            if($parameter->type == 2){
                $private_param[] = $parameter;
            }
        }
        $taxi_param = array();
        foreach($parameters as $parameter){
            if($parameter->type == 3){
                $taxi_param[] = $parameter;
            }
        }
        $bus_param = array();
        foreach($parameters as $parameter){
            if($parameter->type == 4){
                $bus_param[] = $parameter;
            }
        }
        $cc_param = array();
        foreach($parameters as $parameter){
            if($parameter->type == 5){
                $cc_param[] = $parameter;
            }
        }
        $passanger_param = array();
        foreach($parameters as $parameter){
            if($parameter->type == 6){
                $passanger_param[] = $parameter;
            }
        }
        $custom_param = array();
        foreach($parameters as $parameter){
            if($parameter->type == 7){
                $custom_param[] = $parameter;
            }
        }
        $page               = 'Sub Category TP Calculation Parameter';
        $icon               = 'category.png';
        if(Auth::user()->role == 1){
            return view('admin.sub_categories.tpcalculation',compact('category','parameters','page','icon','public_param','private_param','taxi_param','bus_param','cc_param','passanger_param','custom_param'));
        }else{
            return redirect()->route('login');
        }
    }
    public function UpdateTpCalculationParameters(Request $request){
        // echo '<pre>';
        // print_r($request->All());
        // exit;
        if($request->has('public')){
            foreach($request->public as $public){
                if(isset($public['id'])){
                    $parameter = Parameter::where('id',$public['id'])->first();
                    $parameter->carrier         = $public['carrier'];
                    $parameter->carrier_value   = $public['value'];
                    $parameter->save();
                }else{
                    $parameter = new Parameter();
                    $parameter->sub_category_id = $request->id;
                    $parameter->type            = 1;
                    $parameter->carrier_type    = 'public';
                    $parameter->carrier         = $public['carrier'];
                    $parameter->carrier_value   = $public['value'];
                    $parameter->status          = 1;
                    $parameter->save();
                }
            }
        }
        if($request->has('private')){
            foreach($request->private as $private){
                if(isset($private['id'])){
                    $parameter = Parameter::where('id',$private['id'])->first();
                    $parameter->carrier         = $private['carrier'];
                    $parameter->carrier_value   = $private['value'];
                    $parameter->save();
                }else{
                    $parameter = new Parameter();
                    $parameter->sub_category_id = $request->id;
                    $parameter->type            = 2;
                    $parameter->carrier_type    = 'private';
                    $parameter->carrier         = $private['carrier'];
                    $parameter->carrier_value   = $private['value'];
                    $parameter->status          = 1;
                    $parameter->save();
                }
            }
        }
        if($request->has('taxi')){
            foreach($request->taxi as $id=>$taxi){
                if($id != 0){
                    if(isset($taxi['id'])){
                        $parameter = Parameter::where('id',$taxi['id'])->first();
                        $parameter->taxi_cc                 = $taxi['taxi_cc'];
                        $parameter->taxi_cc_value           = $taxi['taxi_cc_value'];
                        $parameter->seating_capacity_rate   = $taxi['seating_capacity_rate'];
                        $parameter->save();
                    }else{
                        $parameter = new Parameter();
                        $parameter->sub_category_id         = $request->id;
                        $parameter->type                    = 3;
                        $parameter->taxi_cc                 = $taxi['taxi_cc'];
                        $parameter->taxi_cc_value           = $taxi['taxi_cc_value'];
                        $parameter->seating_capacity_rate   = $taxi['seating_capacity_rate'];
                        $parameter->status                  = 1;
                        $parameter->save();
                    }
                }else{
                    if(isset($taxi['id'])){
                        $parameter = Parameter::where('id',$taxi['id'])->where('label','paid_driver')->first();
                        $parameter->taxi_value              = $taxi['value'];
                        $parameter->save();
                    }else{
                        $parameter = new Parameter();
                        $parameter->sub_category_id         = $request->id;
                        $parameter->type                    = 3;
                        $parameter->label                   = 'paid_driver';
                        $parameter->taxi_value              = $taxi['value'];
                        $parameter->status                  = 1;
                        $parameter->save();
                    }
                }
            }
        }
        if($request->has('bus')){
            foreach($request->bus as $bus){
                if(isset($bus['id'])){
                    $parameter = Parameter::where('id',$bus['id'])->first();
                    $parameter->label           = $bus['label'];
                    $parameter->carrier_value   = $bus['value'];
                    $parameter->display_type    = $bus['display_type'];
                    $parameter->save();
                }else{
                    $parameter = new Parameter();
                    $parameter->sub_category_id = $request->id;
                    $parameter->type            = 4;
                    $parameter->label           = $bus['label'];
                    $parameter->carrier_value   = $bus['value'];
                    $parameter->display_type    = $bus['display_type'];
                    $parameter->status          = 1;
                    $parameter->save();
                }
            }
        }
        if($request->has('cc')){
            foreach($request->cc as $cc){
                if(isset($cc['id'])){
                    $parameter = Parameter::where('id',$cc['id'])->first();
                    $parameter->label           = $cc['label'];
                    $parameter->carrier_value   = $cc['value'];
                    $parameter->save();
                }else{
                    $parameter = new Parameter();
                    $parameter->sub_category_id = $request->id;
                    $parameter->type            = 5;
                    $parameter->label           = $cc['label'];
                    $parameter->carrier_value   = $cc['value'];
                    $parameter->status          = 1;
                    $parameter->save();
                }
            }
        }
        if($request->has('passanger')){
            foreach($request->passanger as $passanger){
                if(isset($passanger['id'])){
                    $parameter = Parameter::where('id',$passanger['id'])->first();
                    $parameter->label           = $passanger['label'];
                    $parameter->carrier_value   = $passanger['value'];
                    $parameter->save();
                }else{
                    $parameter = new Parameter();
                    $parameter->sub_category_id = $request->id;
                    $parameter->type            = 6;
                    $parameter->label           = $passanger['label'];
                    $parameter->carrier_value   = $passanger['value'];
                    $parameter->status          = 1;
                    $parameter->save();
                }
            }
        }
        if($request->has('custom')){
            foreach($request->custom as $custom){
                if(isset($custom['id'])){
                    $parameter = Parameter::where('id',$custom['id'])->first();
                    $parameter->label           = $custom['label'];
                    $parameter->display_type    = $custom['display_type'];
                    $parameter->carrier_value   = $custom['value'];
                    $parameter->save();
                }else{
                    $parameter = new Parameter();
                    $parameter->sub_category_id = $request->id;
                    $parameter->type            = 7;
                    $parameter->label           = $custom['label'];
                    $parameter->display_type    = $custom['display_type'];
                    $parameter->carrier_value   = $custom['value'];
                    $parameter->status          = 1;
                    $parameter->save();
                }
            }
        }
        return redirect()->back();
    }
    public function deleteParameter($id){
        $parameter = Parameter::where('id',$id)->first();
        $user1 = User::where('id',Auth::user()->id)->first();
        $log = new Log();
        $log->user_id   = Auth::user()->id;
        $log->module    = 'Parameter';
        $log->log       = 'Parameter ('.$parameter->id.') Deleted';
        $log->save();
        $parameter->delete();
        return 1;
    }
    public function deleteAllParameter(Request $request){
        $id = $request->id;
        $category = $request->cat_id;

        $user1 = User::where('id',Auth::user()->id)->first();
        $log = new Log();
        $log->user_id   = Auth::user()->id;
        $log->module    = 'Parameter';
        $log->log       = 'Parameters Deleted';
        $log->save();
        $parameter = Parameter::where('type',$id)->where('sub_category_id',$category)->delete();
        // $parameter->delete();
        return 1;
    }
    public function getCategoryParameters(Request $request){
        $id = $request->id;
        if($request->has('edit')){
            $edit = $request->edit;;
            $policy_parameter = PolicyParameter::where('policy_id',$edit)->get();
        }else{
            $edit = 0;
            $policy_parameter = [];
        }
        $parameters = Parameter::where('sub_category_id',$id)->get();
        $public     = Parameter::where('sub_category_id',$id)->where('type',1)->get();
        $private    = Parameter::where('sub_category_id',$id)->where('type',2)->get();
        $taxi       = Parameter::where('sub_category_id',$id)->where('type',3)->get();
        $bus        = Parameter::where('sub_category_id',$id)->where('type',4)->get();
        $cc         = Parameter::where('sub_category_id',$id)->where('type',5)->get();
        $passenger  = Parameter::where('sub_category_id',$id)->where('type',6)->get();
        $custom     = Parameter::where('sub_category_id',$id)->where('type',7)->get();

        $html = '';
        if(count($public) > 0 || count($private) > 0){
            $html .= '<div class="col-md-6 form-floating mt-4">';
            $html .= '<select class="form-control select2" name="gcv_type" data-id="'.$id.'" id="GcvType" placeholder="" required>';
            $html .= '<option value="0">Select GCV Type...</option>';
            $html .= '<option value="1"';
            if(!blank($policy_parameter)){
                foreach($policy_parameter as $p_param){
                    if($p_param->type == 1){
                        $html .= 'selected';
                    }
                }
            }
            $html .= '>Public</option>';
            $html .= '<option value="2"';
            if(!blank($policy_parameter)){
                foreach($policy_parameter as $p_param){
                    if($p_param->type == 2){
                        $html .= 'selected';
                    }
                }
            }
            $html .= '>Private</option>';
            $html .= '</select>';
            $html .= '<label for="GcvType" class="form-label">GCV Type *</label>';
            $html .= '</div>';
            $html .= '<div class="GCVCarrier col-md-6 form-floating mt-4"';
            $pr = 0;
            if(!blank($policy_parameter)){
                foreach($policy_parameter as $p_param){
                    if($p_param->type == 1 || $p_param->type == 2){
                        $pr++;
                        $html .= '>';
                    }
                }
            }
            if($pr == 0){
                $html .= 'style="display:none;">';
            }
            if(!blank($policy_parameter)){
                // print_r($policy_parameter);
                // exit;
                foreach($policy_parameter as $p_param){
                    if($p_param->type == 1){
                        $public     = Parameter::where('sub_category_id',$id)->where('type',1)->get();
                        if(count($public)>0){
                            $html .= '<select class="form-control select2" name="param[public][value]" id="PublicCarrier" placeholder="" required>';
                            $html .= '<option value="0">Select Public Carrier...</option>';
                            foreach($public as $public_param){
                                $html .= '<option value="'.$public_param->id.'"';
                                    if($p_param->parameter_id == $public_param->id){
                                        $html .= 'selected';
                                    }
                                $html .= '>'.$public_param->carrier.'</option>';
                            }
                            $html .= '</select>';
                            $html .= '<label for="PublicCarrier" class="form-label">Public Carrier *</label>';
                            if($edit != 0){
                                $html .= '<input type="hidden" name="param[public][edit]" value="'.$edit.'">';
                            }
                        }
                    }elseif($p_param->type == 2){
                        $private    = Parameter::where('sub_category_id',$id)->where('type',2)->get();
                        if(count($private)>0){
                            $html .= '<select class="form-control select2" name="param[private][value]" id="PrivateCarrier" placeholder="" required>';
                            $html .= '<option value="1">Select Private Carrier...</option>';
                            foreach($private as $private_param){
                                $html .= '<option value="'.$private_param->id.'"';
                                if(!blank($policy_parameter)){
                                    foreach($policy_parameter as $p_param){
                                        if($p_param->type = 2 && $private_param->id == $p_param->parameter_id){
                                            $html .= 'selected';
                                        }
                                    }
                                }
                                $html .= '>'.$private_param->carrier.'</option>';
                            }
                            $html .= '</select>';
                            $html .= '<label for="PrivateCarrier" class="form-label">Private Carrier *</label>';
                            if($edit != 0){
                                $html .= '<input type="hidden" name="param[private][edit]" value="'.$edit.'">';
                            }
                        }
                    }
                }
            }

            $html .= '</div>';
        }
        if(count($bus)>0 || count($taxi)>0){
            $html .= '<div class="col-md-6 form-floating mt-4">';
            $html .= '<select class="form-control select2" name="pcv_type" id="PcvType" data-id="'.$id.'" placeholder="" required>';
            $html .= '<option value="0">Select PCV Type...</option>';
            $html .= '<option value="1"';
            if(!blank($policy_parameter)){
                $select = 0;
                foreach($policy_parameter as $p_param){
                    if($p_param->type == 3){
                        $select++;
                    }
                }
                if($select > 0){
                    $html .= 'selected';
                }
            }
            $html .= '>Taxi</option>';
            $html .= '<option value="2"';
            if(!blank($policy_parameter)){
                $select = 0;
                foreach($policy_parameter as $p_param){
                    if($p_param->type == 4){
                        $select++;
                    }
                }
                if($select > 0){
                    $html .= 'selected';
                }
            }
            $html .= '>Bus</option>';
            $html .= '</select>';
            $html .= '<label for="PcvType" class="form-label">PCV Type *</label>';
            $html .= '</div>';
            $html .= '<div class="PCVCarrier row mt-4"';
            $pr = 0;
            if(!blank($policy_parameter)){
                foreach($policy_parameter as $p_param){
                    if($p_param->type == 3 || $p_param->type == 4){
                        $pr++;
                       
                    }
                }
                 $html .= '>';
            }
            if($pr == 0){
                $html .= 'style="display:none;">';
            }
            if(!blank($policy_parameter)){
                foreach($policy_parameter as $p_param){
                    if($p_param->type == 3){
                        $taxi     = Parameter::where('sub_category_id',$id)->where('type',3)->get();
                        if(count($taxi)>0){
                            $html .= '<div class="col-md-6 form-floating mt-4">';
                            $html .= '<select class="form-control select2" name="param[taxi][cc][value]" id="CC" placeholder="" required>';
                            $html .= '<option value="0">Select CC...</option>';
                            foreach($taxi as $cc_item){
                                $html .= '<option value="'.$cc_item->id.'">'.$cc_item->taxi_cc.'</option>';
                            }
                            $html .= '</select>';
                            $html .= '<label for="CC" class="form-label">CC *</label>';
                            if($edit != 0){
                                $html .= '<input type="hidden" name="param[taxi][cc][edit]" value="1">';
                            }
                            $html .= '</div>';
                            $html .= '<div class="col-md-6 form-floating mt-4">';
                            $html .= '<select class="form-control select2" name="param[taxi][seating_capacity][value]" id="SeatingCapacity" placeholder="" required>';
                            $html .= '<option value="0">Select Seating Capacity...</option>';
                            $html .= '<option value="4">4</option>';
                            $html .= '<option value="5">5</option>';
                            $html .= '<option value="6">6</option>';
                            $html .= '</select>';
                            $html .= '<label for="SeatingCapacity" class="form-label">Seating Capacity *</label>';
                            if($edit != 0){
                                $html .= '<input type="hidden" name="param[taxi][seating_capacity][edit]" value="1">';
                            }
                            $html .= '</div>';
                            $html .= '<div class="col-md-6  mt-4">';
                            $html .= '<input type="checkbox" value="1" name="param[taxi][paid_driver][value]" id="PaidDriver" checked>';
                            $html .= '<label for="PaidDriver" class="ms-2 form-label">Paid Driver *</label>';
                            if($edit != 0){
                                $html .= '<input type="hidden" name="param[taxi][paid_driver][edit]" value="1">';
                            }
                            $html .= '</div>';
                        }
                    }elseif($p_param->type == 4){
                        $bus    = Parameter::where('sub_category_id',$id)->where('type',4)->get();
                        if(count($bus)>0){
                            foreach($bus as $c_item){
                                if($c_item->display_type == 'dropdown'){
                                    $html .= '<div class="col-md-6 form-floating mt-4">';
                                    $html .= '<select class="form-control select2" name="param[bus]['.$c_item->id.'][value]" id="'.$c_item->label.'" placeholder="" required>';
                                    $html .= '<option value="0">Select '.$c_item->label.'</option>';
                                    for($i = 0; $i <= 100; $i++){
                                        $html .= '<option value="'.$i.'"';
                                        if(!blank($policy_parameter)){
                                            foreach($policy_parameter as $p_param){
                                                if($p_param->type = 4 && $c_item->id == $p_param->parameter_id && $p_param->value == $i){
                                                    $html .= 'selected';
                                                }
                                            }
                                        }
                                        $html .= '>'.$i.'</option>';
                                    }
                                    $html .= '</select>';
                                    $html .= '<label for="'.$c_item->label.'" class="form-label">'.$c_item->label.' *</label>';
                                    if($edit != 0){
                                        $html .= '<input type="hidden" name="param[bus]['.$c_item->id.'][edit]" value="1">';
                                    }
                                    $html .= '</div>';
                                }elseif($c_item->display_type == 'text'){
                                    $html .= '<div class="col-md-6 form-floating mt-4">';
                                    $html .= '<input type="text" class="form-control select2" name="param[bus]['.$c_item->id.'][value]" id="'.$c_item->label.'" value="';
                                    if(!blank($policy_parameter)){
                                        $p = 0;
                                        foreach($policy_parameter as $p_param){
                                            if($p_param->type = 4 && $c_item->id == $p_param->parameter_id){
                                                $html .= $p_param->value;
                                                $p++;
                                            }
                                        }
                                        if($p == 0){
                                            $html .= $c_item->carrier_value;
                                        }
                                    }
                                    $html .= '" placeholder="" required>';
                                    $html .= '<label for="'.$c_item->label.'" class="form-label">'.$c_item->label.' *</label>';
                                    if($edit != 0){
                                        $html .= '<input type="hidden" name="param[bus]['.$c_item->id.'][edit]" value="1">';
                                    }
                                    $html .= '</div>';
                                }elseif($c_item->display_type == 'hidden_field'){
                                    $html .= '<input type="hidden" class="form-control select2" name="param[bus]['.$c_item->id.'][value]" id="'.$c_item->label.'" value="';
                                    if(!blank($policy_parameter)){
                                        $p = 0;
                                        foreach($policy_parameter as $p_param){
                                            if($p_param->type = 4 && $c_item->id == $p_param->parameter_id){
                                                $html .= $p_param->value;
                                                $p++;
                                            }
                                        }
                                        if($p == 0){
                                            $html .= $c_item->carrier_value;
                                        }
                                    }
                                    $html .= '" placeholder="">';
                                    if($edit != 0){
                                        $html .= '<input type="hidden" name="param[bus]['.$c_item->id.'][edit]" value="1">';
                                    }
                                }
                            }
                        }
                    }
                }
            }
            $html .= '</div>';
        }
        if(count($cc)>0){
            $html .= '<div class="col-md-6 form-floating mt-4">';
            $html .= '<select class="form-control select2" name="param[cc][value]" id="CC" placeholder="" required>';
            $html .= '<option value="0">Select CC...</option>';
            foreach($cc as $cc_item){
                $html .= '<option value="'.$cc_item->id.'"';
                if(!blank($policy_parameter)){
                    foreach($policy_parameter as $p_param){
                        if($p_param->type = 5 && $cc_item->id == $p_param->parameter_id){
                            $html .= 'selected';
                        }
                    }
                }
                $html .= '>'.$cc_item->label.'</option>';
            }
            $html .= '</select>';
            $html .= '<label for="CC" class="form-label">CC *</label>';
            if($edit != 0){
                $html .= '<input type="hidden" name="param[cc][edit]" value="'.$edit.'">';
            }
            $html .= '</div>';
        }
        if(count($passenger)>0){
            $html .= '<div class="col-md-6 form-floating mt-4">';
            $html .= '<select class="form-control select2" name="param[pa_to_passanger][value]" id="PaToPassanger" placeholder="" required>';
            $html .= '<option value="0">Select PA to Passanger...</option>';
            foreach($passenger as $pa_item){
                $html .= '<option value="'.$pa_item->id.'"';
                if(!blank($policy_parameter)){
                    foreach($policy_parameter as $p_param){
                        if($p_param->type = 6 && $pa_item->id == $p_param->parameter_id){
                            $html .= 'selected';
                        }
                    }
                }
                $html .= '>'.$pa_item->label.'</option>';
            }
            $html .= '</select>';
            $html .= '<label for="PaToPassanger" class="form-label">PA to Passenger *</label>';
            if($edit != 0){
                $html .= '<input type="hidden" name="param[pa_to_passanger][edit]" value="'.$edit.'">';
            }
            $html .= '</div>';
        }
        if(count($custom) > 0 ){
            foreach($custom as $c_item){
                if($c_item->display_type == 'dropdown'){
                    $html .= '<div class="col-md-6 form-floating mt-4">';
                    $html .= '<select class="form-control select2" name="param[custom]['.$c_item->id.'][value]" id="'.$c_item->label.'" placeholder="" required>';
                    $html .= '<option value="0">Select '.$c_item->label.'</option>';
                    for($i = 1; $i <= 100; $i++){
                        $html .= '<option value="'.$i.'"';
                        if(!blank($policy_parameter)){
                            foreach($policy_parameter as $p_param){
                                if($p_param->type = 7 && $c_item->id == $p_param->parameter_id && $i == $p_param->value){
                                    $html .= 'selected';
                                }
                            }
                        }
                        $html .= '>'.$i.'</option>';
                    }
                    $html .= '</select>';
                    $html .= '<label for="'.$c_item->label.'" class="form-label">'.$c_item->label.' *</label>';
                    if($edit != 0){
                        $html .= '<input type="hidden" name="param[custom]['.$c_item->id.'][edit]" value="'.$edit.'">';
                    }
                    $html .= '</div>';
                }elseif($c_item->display_type == 'text'){
                    $html .= '<div class="col-md-6 form-floating mt-4">';
                    $html .= '<input type="text" class="form-control select2" name="param[custom]['.$c_item->id.'][value]" id="'.$c_item->label.'" value="';
                    if(!blank($policy_parameter)){
                        $p = 0;
                        foreach($policy_parameter as $p_param){
                            if($p_param->type = 7 && $c_item->id == $p_param->parameter_id){
                                $html .= $p_param->value;
                                $p++;
                            }
                        }
                        if($p == 0){
                            $html .= $c_item->carrier_value;
                        }
                    }
                    $html .= '" placeholder="" required>';
                    $html .= '<label for="'.$c_item->label.'" class="form-label">'.$c_item->label.' *</label>';
                    if($edit != 0){
                        $html .= '<input type="hidden" name="param[custom]['.$c_item->id.'][edit]" value="'.$edit.'">';
                    }
                    $html .= '</div>';
                }elseif($c_item->display_type == 'hidden_field'){
                    $html .= '<input type="hidden" class="form-control select2 12" name="param[custom]['.$c_item->id.'][value]" id="'.$c_item->label.'" value="';
                    if(!blank($policy_parameter)){
                        $p = 0;
                        foreach($policy_parameter as $p_param){
                            if($p_param->type = 7 && $c_item->id == $p_param->parameter_id){
                                $html .= $p_param->value;
                                $p++;
                            }
                        }
                        if($p == 0){
                            $html .= $c_item->carrier_value;
                        }
                    }
                    $html .= '" placeholder="" required>';
                    if($edit != 0){
                        $html .= '<input type="hidden" name="param[custom]['.$c_item->id.'][edit]" value="'.$edit.'">';
                    }
                }
            }
        }
        return response()->json($html, 200);
    }
    public function getCategoryCovernoteParameters(Request $request){
        $id = $request->id;
        if($request->has('edit')){
            $edit = $request->edit;;
            $policy_parameter = CovernoteParameter::where('covernote_id',$edit)->get();
        }else{
            $edit = 0;
            $policy_parameter = [];
        }
        $parameters = Parameter::where('sub_category_id',$id)->get();
        $public     = Parameter::where('sub_category_id',$id)->where('type',1)->get();
        $private    = Parameter::where('sub_category_id',$id)->where('type',2)->get();
        $taxi       = Parameter::where('sub_category_id',$id)->where('type',3)->get();
        $bus        = Parameter::where('sub_category_id',$id)->where('type',4)->get();
        $cc         = Parameter::where('sub_category_id',$id)->where('type',5)->get();
        $passenger  = Parameter::where('sub_category_id',$id)->where('type',6)->get();
        $custom     = Parameter::where('sub_category_id',$id)->where('type',7)->get();

        $html = '';
        if(count($public) > 0 || count($private) > 0){
            $html .= '<div class="col-md-6 form-floating mt-4">';
            $html .= '<select class="form-control select2" name="gcv_type" data-id="'.$id.'" id="GcvType" placeholder="" required>';
            $html .= '<option value="0">Select GCV Type...</option>';
            $html .= '<option value="1"';
            if(!blank($policy_parameter)){
                foreach($policy_parameter as $p_param){
                    if($p_param->type == 1){
                        $html .= 'selected';
                    }
                }
            }
            $html .= '>Public</option>';
            $html .= '<option value="2"';
            if(!blank($policy_parameter)){
                foreach($policy_parameter as $p_param){
                    if($p_param->type == 2){
                        $html .= 'selected';
                    }
                }
            }
            $html .= '>Private</option>';
            $html .= '</select>';
            $html .= '<label for="GcvType" class="form-label">GCV Type *</label>';
            $html .= '</div>';
            $html .= '<div class="GCVCarrier col-md-6 form-floating mt-4"';
            $pr = 0;
            if(!blank($policy_parameter)){
                foreach($policy_parameter as $p_param){
                    if($p_param->type == 1 || $p_param->type == 2){
                        $pr++;
                        $html .= '>';
                    }
                }
            }
            if($pr == 0){
                $html .= 'style="display:none;">';
            }
            if(!blank($policy_parameter)){
                // print_r($policy_parameter);
                // exit;
                foreach($policy_parameter as $p_param){
                    if($p_param->type == 1){
                        $public     = Parameter::where('sub_category_id',$id)->where('type',1)->get();
                        if(count($public)>0){
                            $html .= '<select class="form-control select2" name="param[public][value]" id="PublicCarrier" placeholder="" required>';
                            $html .= '<option value="0">Select Public Carrier...</option>';
                            foreach($public as $public_param){
                                $html .= '<option value="'.$public_param->id.'"';
                                    if($p_param->parameter_id == $public_param->id){
                                        $html .= 'selected';
                                    }
                                $html .= '>'.$public_param->carrier.'</option>';
                            }
                            $html .= '</select>';
                            $html .= '<label for="PublicCarrier" class="form-label">Public Carrier *</label>';
                            if($edit != 0){
                                $html .= '<input type="hidden" name="param[public][edit]" value="'.$edit.'">';
                            }
                        }
                    }elseif($p_param->type == 2){
                        $private    = Parameter::where('sub_category_id',$id)->where('type',2)->get();
                        if(count($private)>0){
                            $html .= '<select class="form-control select2" name="param[private][value]" id="PrivateCarrier" placeholder="" required>';
                            $html .= '<option value="1">Select Private Carrier...</option>';
                            foreach($private as $private_param){
                                $html .= '<option value="'.$private_param->id.'"';
                                if(!blank($policy_parameter)){
                                    foreach($policy_parameter as $p_param){
                                        if($p_param->type = 2 && $private_param->id == $p_param->parameter_id){
                                            $html .= 'selected';
                                        }
                                    }
                                }
                                $html .= '>'.$private_param->carrier.'</option>';
                            }
                            $html .= '</select>';
                            $html .= '<label for="PrivateCarrier" class="form-label">Private Carrier *</label>';
                            if($edit != 0){
                                $html .= '<input type="hidden" name="param[private][edit]" value="'.$edit.'">';
                            }
                        }
                    }
                }
            }

            $html .= '</div>';
        }
        if(count($bus)>0 || count($taxi)>0){
            $html .= '<div class="col-md-6 form-floating mt-4">';
            $html .= '<select class="form-control select2" name="pcv_type" id="PcvType" data-id="'.$id.'" placeholder="" required>';
            $html .= '<option value="0">Select PCV Type...</option>';
            $html .= '<option value="1"';
            if(!blank($policy_parameter)){
                $select = 0;
                foreach($policy_parameter as $p_param){
                    if($p_param->type == 3){
                        $select++;
                    }
                }
                if($select > 0){
                    $html .= 'selected';
                }
            }
            $html .= '>Taxi</option>';
            $html .= '<option value="2"';
            if(!blank($policy_parameter)){
                $select = 0;
                foreach($policy_parameter as $p_param){
                    if($p_param->type == 4){
                        $select++;
                    }
                }
                if($select > 0){
                    $html .= 'selected';
                }
            }
            $html .= '>Bus</option>';
            $html .= '</select>';
            $html .= '<label for="PcvType" class="form-label">PCV Type *</label>';
            $html .= '</div>';
            $html .= '<div class="PCVCarrier row mt-4"';
            $pr = 0;
            if(!blank($policy_parameter)){
                foreach($policy_parameter as $p_param){
                    if($p_param->type == 3 || $p_param->type == 4){
                        $pr++;
                      
                    }
                }
                  $html .= '>';
            }
            if($pr == 0){
                $html .= 'style="display:none;">';
            }
            if(!blank($policy_parameter)){
                foreach($policy_parameter as $p_param){
                    if($p_param->type == 3){
                        $taxi     = Parameter::where('sub_category_id',$id)->where('type',3)->get();
                        if(count($taxi)>0){
                            $html .= '<div class="col-md-6 form-floating mt-4">';
                            $html .= '<select class="form-control select2" name="param[taxi][cc][value]" id="CC" placeholder="" required>';
                            $html .= '<option value="0">Select CC...</option>';
                            foreach($taxi as $cc_item){
                                $html .= '<option value="'.$cc_item->id.'">'.$cc_item->taxi_cc.'</option>';
                            }
                            $html .= '</select>';
                            $html .= '<label for="CC" class="form-label">CC *</label>';
                            if($edit != 0){
                                $html .= '<input type="hidden" name="param[taxi][cc][edit]" value="1">';
                            }
                            $html .= '</div>';
                            $html .= '<div class="col-md-6 form-floating mt-4">';
                            $html .= '<select class="form-control select2" name="param[taxi][seating_capacity][value]" id="SeatingCapacity" placeholder="" required>';
                            $html .= '<option value="0">Select Seating Capacity...</option>';
                            $html .= '<option value="4">4</option>';
                            $html .= '<option value="5">5</option>';
                            $html .= '<option value="6">6</option>';
                            $html .= '</select>';
                            $html .= '<label for="SeatingCapacity" class="form-label">Seating Capacity *</label>';
                            if($edit != 0){
                                $html .= '<input type="hidden" name="param[taxi][seating_capacity][edit]" value="1">';
                            }
                            $html .= '</div>';
                            $html .= '<div class="col-md-6  mt-4">';
                            $html .= '<input type="checkbox" value="1" name="param[taxi][paid_driver][value]" id="PaidDriver" checked>';
                            $html .= '<label for="PaidDriver" class="ms-2 form-label">Paid Driver *</label>';
                            if($edit != 0){
                                $html .= '<input type="hidden" name="param[taxi][paid_driver][edit]" value="1">';
                            }
                            $html .= '</div>';
                        }
                    }elseif($p_param->type == 4){
                        $bus    = Parameter::where('sub_category_id',$id)->where('type',4)->get();
                        if(count($bus)>0){
                            foreach($bus as $c_item){
                                if($c_item->display_type == 'dropdown'){
                                    $html .= '<div class="col-md-6 form-floating mt-4">';
                                    $html .= '<select class="form-control select2" name="param[bus]['.$c_item->id.'][value]" id="'.$c_item->label.'" placeholder="" required>';
                                    $html .= '<option value="0">Select '.$c_item->label.'</option>';
                                    for($i = 0; $i <= 100; $i++){
                                        $html .= '<option value="'.$i.'"';
                                        if(!blank($policy_parameter)){
                                            foreach($policy_parameter as $p_param){
                                                if($p_param->type = 4 && $c_item->id == $p_param->parameter_id && $p_param->value == $i){
                                                    $html .= 'selected';
                                                }
                                            }
                                        }
                                        $html .= '>'.$i.'</option>';
                                    }
                                    $html .= '</select>';
                                    $html .= '<label for="'.$c_item->label.'" class="form-label">'.$c_item->label.' *</label>';
                                    if($edit != 0){
                                        $html .= '<input type="hidden" name="param[bus]['.$c_item->id.'][edit]" value="1">';
                                    }
                                    $html .= '</div>';
                                }elseif($c_item->display_type == 'text'){
                                    $html .= '<div class="col-md-6 form-floating mt-4">';
                                    $html .= '<input type="text" class="form-control select2" name="param[bus]['.$c_item->id.'][value]" id="'.$c_item->label.'" value="';
                                    if(!blank($policy_parameter)){
                                        $p = 0;
                                        foreach($policy_parameter as $p_param){
                                            if($p_param->type = 4 && $c_item->id == $p_param->parameter_id){
                                                $html .= $p_param->value;
                                                $p++;
                                            }
                                        }
                                        if($p == 0){
                                            $html .= $c_item->carrier_value;
                                        }
                                    }
                                    $html .= '" placeholder="" required>';
                                    $html .= '<label for="'.$c_item->label.'" class="form-label">'.$c_item->label.' *</label>';
                                    if($edit != 0){
                                        $html .= '<input type="hidden" name="param[bus]['.$c_item->id.'][edit]" value="1">';
                                    }
                                    $html .= '</div>';
                                }elseif($c_item->display_type == 'hidden_field'){
                                    $html .= '<input type="hidden" class="form-control select2" name="param[bus]['.$c_item->id.'][value]" id="'.$c_item->label.'" value="';
                                    if(!blank($policy_parameter)){
                                        $p = 0;
                                        foreach($policy_parameter as $p_param){
                                            if($p_param->type = 4 && $c_item->id == $p_param->parameter_id){
                                                $html .= $p_param->value;
                                                $p++;
                                            }
                                        }
                                        if($p == 0){
                                            $html .= $c_item->carrier_value;
                                        }
                                    }
                                    $html .= '" placeholder="">';
                                    if($edit != 0){
                                        $html .= '<input type="hidden" name="param[bus]['.$c_item->id.'][edit]" value="1">';
                                    }
                                }
                            }
                        }
                    }
                }
            }
            $html .= '</div>';
        }
        if(count($cc)>0){
            $html .= '<div class="col-md-6 form-floating mt-4">';
            $html .= '<select class="form-control select2" name="param[cc][value]" id="CC" placeholder="" required>';
            $html .= '<option value="0">Select CC...</option>';
            foreach($cc as $cc_item){
                $html .= '<option value="'.$cc_item->id.'"';
                if(!blank($policy_parameter)){
                    foreach($policy_parameter as $p_param){
                        if($p_param->type = 5 && $cc_item->id == $p_param->parameter_id){
                            $html .= 'selected';
                        }
                    }
                }
                $html .= '>'.$cc_item->label.'</option>';
            }
            $html .= '</select>';
            $html .= '<label for="CC" class="form-label">CC *</label>';
            if($edit != 0){
                $html .= '<input type="hidden" name="param[cc][edit]" value="'.$edit.'">';
            }
            $html .= '</div>';
        }
        if(count($passenger)>0){
            $html .= '<div class="col-md-6 form-floating mt-4">';
            $html .= '<select class="form-control select2" name="param[pa_to_passanger][value]" id="PaToPassanger" placeholder="" required>';
            $html .= '<option value="0">Select PA to Passanger...</option>';
            foreach($passenger as $pa_item){
                $html .= '<option value="'.$pa_item->id.'"';
                if(!blank($policy_parameter)){
                    foreach($policy_parameter as $p_param){
                        if($p_param->type = 6 && $pa_item->id == $p_param->parameter_id){
                            $html .= 'selected';
                        }
                    }
                }
                $html .= '>'.$pa_item->label.'</option>';
            }
            $html .= '</select>';
            $html .= '<label for="PaToPassanger" class="form-label">PA to Passenger *</label>';
            if($edit != 0){
                $html .= '<input type="hidden" name="param[pa_to_passanger][edit]" value="'.$edit.'">';
            }
            $html .= '</div>';
        }
        if(count($custom) > 0 ){
            foreach($custom as $c_item){
                if($c_item->display_type == 'dropdown'){
                    $html .= '<div class="col-md-6 form-floating mt-4">';
                    $html .= '<select class="form-control select2" name="param[custom]['.$c_item->id.'][value]" id="'.$c_item->label.'" placeholder="" required>';
                    $html .= '<option value="0">Select '.$c_item->label.'</option>';
                    for($i = 1; $i <= 100; $i++){
                        $html .= '<option value="'.$i.'"';
                        if(!blank($policy_parameter)){
                            foreach($policy_parameter as $p_param){
                                if($p_param->type = 7 && $c_item->id == $p_param->parameter_id && $i == $p_param->value){
                                    $html .= 'selected';
                                }
                            }
                        }
                        $html .= '>'.$i.'</option>';
                    }
                    $html .= '</select>';
                    $html .= '<label for="'.$c_item->label.'" class="form-label">'.$c_item->label.' *</label>';
                    if($edit != 0){
                        $html .= '<input type="hidden" name="param[custom]['.$c_item->id.'][edit]" value="'.$edit.'">';
                    }
                    $html .= '</div>';
                }elseif($c_item->display_type == 'text'){
                    $html .= '<div class="col-md-6 form-floating mt-4">';
                    $html .= '<input type="text" class="form-control select2" name="param[custom]['.$c_item->id.'][value]" id="'.$c_item->label.'" value="';
                    if(!blank($policy_parameter)){
                        $p = 0;
                        foreach($policy_parameter as $p_param){
                            if($p_param->type = 7 && $c_item->id == $p_param->parameter_id){
                                $html .= $p_param->value;
                                $p++;
                            }
                        }
                        if($p == 0){
                            $html .= $c_item->carrier_value;
                        }
                    }
                    $html .= '" placeholder="" required>';
                    $html .= '<label for="'.$c_item->label.'" class="form-label">'.$c_item->label.' *</label>';
                    if($edit != 0){
                        $html .= '<input type="hidden" name="param[custom]['.$c_item->id.'][edit]" value="'.$edit.'">';
                    }
                    $html .= '</div>';
                }elseif($c_item->display_type == 'hidden_field'){
                    $html .= '<input type="hidden" class="form-control select2" name="param[custom]['.$c_item->id.'][value]" id="'.$c_item->label.'" value="';
                    if(!blank($policy_parameter)){
                        $p = 0;
                        foreach($policy_parameter as $p_param){
                            if($p_param->type = 7 && $c_item->id == $p_param->parameter_id){
                                $html .= $p_param->value;
                                $p++;
                            }
                        }
                        if($p == 0){
                            $html .= $c_item->carrier_value;
                        }
                    }
                    $html .= '" placeholder="" required>';
                    if($edit != 0){
                        $html .= '<input type="hidden" name="param[custom]['.$c_item->id.'][edit]" value="'.$edit.'">';
                    }
                }
            }
        }
        return response()->json($html, 200);
    }
    public function getGCVParameters(Request $request){
        $id = $request->id;
        $gcv_type = $request->gcv_type;
        if($request->has('edit')){
            $edit = $request->edit;
            $policy_parameter = PolicyParameter::where('policy_id',$edit)->get();
        }else{
            $edit = 0;
            $policy_parameter = [];
        }
        $html = '';
        if($gcv_type == 1){
            $public     = Parameter::where('sub_category_id',$id)->where('type',1)->get();
            if(count($public)>0){
                $html .= '<select class="form-control select2" name="param[public][value]" id="PublicCarrier" placeholder="" required>';
                $html .= '<option value="0">Select Public Carrier...</option>';
                foreach($public as $public_param){
                    $html .= '<option value="'.$public_param->id.'"';
                    if(!blank($policy_parameter)){
                        foreach($policy_parameter as $p_param){
                            if($p_param->type = 1 && $public_param->id == $p_param->parameter_id){
                                $html .= 'selected';
                            }
                        }
                    }
                    $html .= '>'.$public_param->carrier.'</option>';
                }
                $html .= '</select>';
                $html .= '<label for="PublicCarrier" class="form-label">Public Carrier *</label>';
                if($edit != 0){
                    $html .= '<input type="hidden" name="param[public][edit]" value="'.$edit.'">';
                }
            }
        }elseif($gcv_type == 2){
            $private    = Parameter::where('sub_category_id',$id)->where('type',2)->get();
            if(count($private)>0){
                $html .= '<select class="form-control select2" name="param[private][value]" id="PrivateCarrier" placeholder="" required>';
                $html .= '<option value="1">Select Private Carrier...</option>';
                foreach($private as $private_param){
                    $html .= '<option value="'.$private_param->id.'"';
                    if(!blank($policy_parameter)){
                        foreach($policy_parameter as $p_param){
                            if($p_param->type = 2 && $private_param->id == $p_param->parameter_id){
                                $html .= 'selected';
                            }
                        }
                    }
                    $html .= '>'.$private_param->carrier.'</option>';
                }
                $html .= '</select>';
                $html .= '<label for="PrivateCarrier" class="form-label">Private Carrier *</label>';
                if($edit != 0){
                    $html .= '<input type="hidden" name="param[private][edit]" value="1">';
                }
            }
        }else{
            $html .= '';
        }
        return response()->json($html, 200);
    }
    public function getPCVParameters(Request $request){
        $id = $request->id;
        $pcv_type = $request->pcv_type;
        if($request->has('edit')){
            $edit = $request->edit;
            $policy_parameter = PolicyParameter::where('policy_id',$edit)->get();
        }else{
            $edit = 0;
            $policy_parameter = [];
        }
        $html = '';
        if($pcv_type == 1){
            $taxi     = Parameter::where('sub_category_id',$id)->where('type',3)->get();
            if(count($taxi)>0){
                $html .= '<div class="col-md-6 form-floating mt-4">';
                $html .= '<select class="form-control select2" name="param[taxi][cc][value]" id="CC" placeholder="" required>';
                $html .= '<option value="0">Select CC...</option>';
                foreach($taxi as $cc_item){
                    $html .= '<option value="'.$cc_item->id.'">'.$cc_item->taxi_cc.'</option>';
                }
                $html .= '</select>';
                $html .= '<label for="CC" class="form-label">CC *</label>';
                if($edit != 0){
                    $html .= '<input type="hidden" name="param[taxi][cc][edit]" value="1">';
                }
                $html .= '</div>';
                $html .= '<div class="col-md-6 form-floating mt-4">';
                $html .= '<select class="form-control select2" name="param[taxi][seating_capacity][value]" id="SeatingCapacity" placeholder="" required>';
                $html .= '<option value="0">Select Seating Capacity...</option>';
                $html .= '<option value="4">4</option>';
                $html .= '<option value="5">5</option>';
                $html .= '<option value="6">6</option>';
                $html .= '</select>';
                $html .= '<label for="SeatingCapacity" class="form-label">Seating Capacity *</label>';
                if($edit != 0){
                    $html .= '<input type="hidden" name="param[taxi][seating_capacity][edit]" value="1">';
                }
                $html .= '</div>';
                $html .= '<div class="col-md-6  mt-4">';
                $html .= '<input type="checkbox" value="1" name="param[taxi][paid_driver][value]" id="PaidDriver" checked>';
                $html .= '<label for="PaidDriver" class="ms-2 form-label">Paid Driver *</label>';
                if($edit != 0){
                    $html .= '<input type="hidden" name="param[taxi][paid_driver][edit]" value="1">';
                }
                $html .= '</div>';
            }
        }elseif($pcv_type == 2){
            $bus    = Parameter::where('sub_category_id',$id)->where('type',4)->get();
            if(count($bus)>0){
                foreach($bus as $c_item){
                    if($c_item->display_type == 'dropdown'){
                        $html .= '<div class="col-md-6 form-floating mt-4">';
                        $html .= '<select class="form-control select2" name="param[bus]['.$c_item->id.'][value]" id="'.$c_item->label.'" placeholder="" required>';
                        $html .= '<option value="0">Select '.$c_item->label.'</option>';
                        for($i = 0; $i <= 100; $i++){
                            $html .= '<option value="'.$i.'">'.$i.'</option>';
                        }
                        $html .= '</select>';
                        $html .= '<label for="'.$c_item->label.'" class="form-label">'.$c_item->label.' *</label>';
                        if($edit != 0){
                            $html .= '<input type="hidden" name="param[bus]['.$c_item->id.'][edit]" value="1">';
                        }
                        $html .= '</div>';
                    }elseif($c_item->display_type == 'text'){
                        $html .= '<div class="col-md-6 form-floating mt-4">';
                        $html .= '<input type="text" class="form-control select2" name="param[bus]['.$c_item->id.'][value]" id="'.$c_item->label.'" value="'.$c_item->carrier_value.'" placeholder="" required>';
                        $html .= '<label for="'.$c_item->label.'" class="form-label">'.$c_item->label.' *</label>';
                        if($edit != 0){
                            $html .= '<input type="hidden" name="param[bus]['.$c_item->id.'][edit]" value="1">';
                        }
                        $html .= '</div>';
                    }elseif($c_item->display_type == 'hidden_field'){
                        $html .= '<input type="hidden" class="form-control select2" name="param[bus]['.$c_item->id.'][value]" id="'.$c_item->label.'" value="'.$c_item->carrier_value.'" placeholder="">';
                        if($edit != 0){
                            $html .= '<input type="hidden" name="param[bus]['.$c_item->id.'][edit]" value="1">';
                        }
                    }
                }
            }
        }else{
            $html .= '';
        }
        return response()->json($html, 200);
    }
}
