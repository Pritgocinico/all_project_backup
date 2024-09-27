<?php

namespace App\Http\Controllers\API;

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
use Carbon\Carbon;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use App\Models\AccessToken;

class SubCategoryApiController extends Controller
{
    public function __construct() {
        $setting=Setting::first();
        $user = User::first();
        view()->share('setting', $setting);
    }
    public function subCategories(){
        $sub_categories = Category::where('parent','!=',0)->orderBy('id','Desc')->get();
        $array_push = array();
        if(!blank($sub_categories)){
            foreach($sub_categories as $category){
                $parent = Category::where('id',$category->parent)->first();
                $array = array();
                $array['id']            = $category->id;
                $array['name']          = ($category->name != NULL)?$category->name:"";
                $array['parent']        = ($parent->name != NULL)?$parent->name:"";
                $array['description']   = ($category->description != NULL)?$category->description:"";
                $array['gst_rate']      = ($category->gst != NULL)?$parent->gst:"";
                $array['renewable']     = ($category->renewable != NULL)?$parent->renewable:0;
                $array['created_at']    = ($category->created_at != NULL)?$category->created_at:"";
                array_push($array_push,$array);
            }
            return response()->json([
                'status' => 1,
                'sub_categories'=>$array_push
            ],200);
        }else{
             return response()->json(['status'=>0,'error'=> 'Sub Categories Not Found.'],200);
        }
    }
    public function storeSubCategory(Request $request){
        $validator = Validator::make($request->all(), [
            'name'                  => 'required|unique:categories,name',
            'parent'                => 'required',
            'gst_rate'              => 'required', 
        ]);
        if ($validator->fails()) {
            return response()->json(['status'=>0,'error'=>implode(" ", $validator->errors()->all())], 200);
        }else{
            $category = new Category();
            $category->name         = $request->name;
            $category->parent       = $request->parent;
            $category->gst          = $request->gst_rate;
            $category->renewable    = $request->renewable;
            $category->description  = $request->description;
            $category->created_by   = $request->user_id;
            $category->status       = 1;
            $category->save();

            if($category){
                $user = User::where('id',$request->user_id)->first();
                $log = new Log();
                $log->user_id   = $user->name;
                $log->module    = 'Sub Categories';
                $log->log       = 'Sub Category ('.$request->name.') Created.';
                $log->save();
                return response()->json(['status'=>1,'message'=>'Sub category added successfully.'],200);
            }else{
                return response()->json(['status'=>0,'error'=>'Something went wrong.'],200);
            }
        }
    }
    public function deleteSubCategory(Request $request, $id){
        if(!blank($id)){
            $category = Category::where('id',$id)->first();
            if(!blank($category)){
                $log = new Log();
                $log->user_id   = $category->name;
                $log->module    = 'Sub Category';
                $log->log       = 'Sub Category ('.$category->name .') Deleted.';
                $log->save();
                $category->delete();
            }else{
                return response()->json(['status'=>0,'error'=>'Something Went Wrong.'],200);
            }
            return response()->json(['status'=>1,'message'=>'Sub Category deleted successfully!'],200);
        }else{
           return response()->json(['status'=>0,'error'=>'Something Went Wrong.'],200);
        }
    }
    public function updateSubCategory(Request $request){
        $validator = Validator::make($request->all(), [
            'name'                => 'required|unique:categories,name,'.$request->sub_category_id,
        ]);
        if ($validator->fails()) {
            return response()->json(['status'=>0,'error'=>implode(" ", $validator->errors()->all())], 200);
        }else{
            $category = Category::where('id',$request->sub_category_id)->first();
            $category->name         = $request->name;
            $category->parent       = $request->parent;
            $category->gst          = $request->gst_rate;
            $category->renewable    = $request->renewable;
            $category->description  = $request->description;
            $category->status       = $request->status;
            $category->save();

            if($category){
                $user = User::where('id',$request->user_id)->first();
                $log = new Log();
                $log->user_id   = $user->name;
                $log->module    = 'Sub Categories';
                $log->log       = 'Sub Category ('.$request->name.') Updated.';
                $log->save();
                return response()->json(['status'=>1,'message'=>'Sub Category updated successfully.'],200);
            }else{
                return response()->json(['status'=>0,'error'=>'Something went wrong.'],200);
            }
        }
    }
}
