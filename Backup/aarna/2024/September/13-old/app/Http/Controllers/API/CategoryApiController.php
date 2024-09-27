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
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use App\Models\AccessToken;

class CategoryApiController extends Controller
{
    public function __construct() {
        $setting=Setting::first();
        view()->share('setting', $setting);
    }
    public function Categories(){
        $categories = Category::where('parent','==',0)->orderBy('id','Desc')->get();
        $array_push = array();
        if(!blank($categories)){
            foreach($categories as $category){
                $array = array();
                $array['id']            = $category->id;
                $array['name']          = ($category->name != NULL)?$category->name:"";
                $array['description']   = ($category->description != NULL)?$category->description:"";
                $array['created_at']    = ($category->created_at != NULL)?$category->created_at:"";
                $array['category_type']    = ($category->insurance_type != NULL)?$category->insurance_type:"";
                array_push($array_push,$array);
            }
            return response()->json([
                'status' => 1,
                'categories'=>$array_push
            ],200);
        }else{
             return response()->json(['status'=>0,'error'=> 'Categories Not Found.'],200);
        }
    }
    public function storeCategory(Request $request){
        $validator = Validator::make($request->all(), [
            'name'                => 'required|unique:categories,name',
        ]);
        if ($validator->fails()) {
            return response()->json(['status'=>0,'error'=>implode(" ", $validator->errors()->all())], 200);
        }else{
            $category = new Category();
            $category->name         = $request->name;
            $category->description  = $request->description;
            $category->created_by   = $request->user_id;
            $category->status       = 1;
            $category->save();

            if($category){
                $user = User::first();
                $log = new Log();
                $log->user_id   = $user->name;
                $log->module    = 'Categories';
                $log->log       = 'Category ('.$request->name.') Created.';
                $log->save();
                return response()->json(['status'=>1,'message'=>'Category added successfully.'],200);
            }else{
                return response()->json(['status'=>0,'error'=>'Something went wrong.'],200);
            }
        }
    }
    public function deleteCategory(Request $request, $id){
        if(!blank($id)){
            $category = Category::where('id',$id)->first();
            if(!blank($category)){
                $log = new Log();
                $log->user_id   = $category->name;
                $log->module    = 'Category';
                $log->log       = 'Category ('.$category->name .') Deleted.';
                $log->save();
                $category->delete();
            }else{
                return response()->json(['status'=>0,'error'=>'Something Went Wrong.'],200);
            }
            return response()->json(['status'=>1,'message'=>'Category deleted successfully!'],200);
        }else{
           return response()->json(['status'=>0,'error'=>'Something Went Wrong.'],200);
        }
    }
    public function updateCategory(Request $request){
        $validator = Validator::make($request->all(), [
            'name'                => 'required|unique:categories,name,'.$request->category_id,
        ]);
        if ($validator->fails()) {
            return response()->json(['status'=>0,'error'=>implode(" ", $validator->errors()->all())], 200);
        }else{
            $category = Category::where('id',$request->category_id)->first();
            $category->name         = $request->name;
            $category->description  = $request->description;
            $category->status       = $request->status;
            $category->save();

            if($category){
                $user = User::first();
                $log = new Log();
                $log->user_id   = $request->user_id;
                $log->module    = 'Categories';
                $log->log       = 'Category ('.$request->name.') Updated.';
                $log->save();
                return response()->json(['status'=>1,'message'=>'Category updated successfully.'],200);
            }else{
                return response()->json(['status'=>0,'error'=>'Something went wrong.'],200);
            }
        }
    }
}
