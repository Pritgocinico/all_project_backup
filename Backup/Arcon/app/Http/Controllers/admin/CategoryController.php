<?php

namespace App\Http\Controllers\admin;

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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Notification;
use App\Notifications\Notifications;
class CategoryController extends Controller
{
    public function __construct() {
        $setting=Setting::first();
        $user = User::first();
        $notification = $user->unreadNotifications;
        view()->share('notifications', $notification);
        view()->share('setting', $setting);
    }
    public function categories(){
        $categories = Category::orderBy('id','Desc')->get();
        $parent_categories = Category::where('parent','==',0)->orderBy('id','Desc')->get();
        $page  = 'Categories';
        $icon  = 'category.png';
        if(Auth::user()->role == 1){
            return view('admin.category.categories',compact('categories','parent_categories','page','icon'));
        }elseif(Auth::user()->role == 3){
            return view('agent.category.categories',compact('categories','parent_categories','page','icon'));
        }else{
            return redirect()->route('login');
        }
    }
    public function addCategoryData(Request $req){
        $req->validate([
            'name'                => 'required|unique:categories,name',
        ]);

        if($req->has('image') && $req->file('image') != null){
            $image = $req->file('image');
            $destinationPath = 'public/categories/';
            $rand=rand(1,100);
            $docImage = date('YmdHis').$rand. "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $docImage);
            $img=$docImage;
        }else{
            $img='';
        }

        $category = new Category();
        $category->name         = $req->name;
        // $category->brand_name   = $req->brand_name;
        $category->parent       = $req->parent;
        $category->image        = $img;
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
        if(Auth::user()->role == 1){
            return redirect()->route('admin.categories');
        }elseif(Auth::user()->role == 3){
            return redirect()->route('agent.categories');
        }else{
            return redirect()->route('login');
        }
    }
    public function editCategory($id){
        $categories         = Category::orderBy('id','Desc')->get();
        $category           = Category::where('id',$id)->first();
        $page               = 'Categories';
        $icon               = 'category.png';
        if(Auth::user()->role == 1){
            return view('admin.category.edit_category',compact('categories','category','page','icon'));
        }elseif(Auth::user()->role == 3){
            return view('agent.category.edit_category',compact('categories','category','page','icon'));
        }else{
            return redirect()->route('login');
        }
    }
    public function updateCategory(Request $req){
        $req->validate([
            'name'                => 'required|unique:categories,name,'.$req->category_id,
        ]);
        if($req->has('image') && $req->file('image') != null){
            $image = $req->file('image');
            $destinationPath = 'public/categories/';
            $rand=rand(1,100);
            $docImage = date('YmdHis').$rand. "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $docImage);
            $img=$docImage;
        }else{
            $img=$req->hidden_image;
        }
        if($req->status == "on"){
            $status = 1;
        }else{
            $status = 0;
        }
        $category = Category::where('id',$req->category_id)->first();
        $category->name         = $req->name;
        // $category->brand_name   = $req->brand_name;
        $category->parent       = $req->parent;
        $category->description  = $req->description;
        $category->image        = $img;
        $category->status       = $status;
        $category->save();

        $user1 = User::where('id',Auth::user()->id)->first();
        $log = new Log();
        $log->user_id   = Auth::user()->name;
        $log->module    = 'Category';
        $log->log       = 'Category ('.$category->name .') Updated';
        $log->save();
        if(Auth::user()->role == 1){
            return redirect()->route('admin.categories');
        }elseif(Auth::user()->role == 3){
            return redirect()->route('agent.categories');
        }else{
            return redirect()->route('login');
        }
    }
    public function deleteCategory($id){
        $category = Category::where('id',$id)->first();
        $user1 = User::where('id',Auth::user()->id)->first();
        $log = new Log();
        $log->user_id   = Auth::user()->id;
        $log->module    = 'Category';
        $log->log       = ' Category ('.$category->name .') Deleted';
        $log->save();
        $category->delete();
        $cat = Category::where('parent',$id)->get();
        if(count($cat)>0){
            Category::where('parent',$id)->delete();
        }
        return 1;
    }
    public function getCategories(Request $req){
        $categories = Category::all();
        $parent_categories = Category::where('parent','==',0)->get();
        $html = '';
        if (count($parent_categories)>0){
            foreach ($parent_categories as $item){
                $childs = DB::table('categories')->where('parent',$item->id)->get();
                if (count($childs)>0){
                    $html .= '<optgroup label="'.$item->name.'">';
                        foreach ($childs as $child){
                            $html .= '<option value="'.$child->id.'">'.$child->name.'</option>';
                        }
                    $html .= '</optgroup>';
                }else{
                    $html .= '<option value="'.$item->id.'">'.$item->name.'</option>';
                }
            }
        }
        return $html;
    }
}
