<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Hash;
use Session;
use App\Models\RoleUser;
use App\Models\User;
use App\Models\Log;
use App\Models\Setting;
use App\Models\GalleryPhoto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Notification;
use App\Notifications\Notifications;

class GalleryController extends Controller
{
    public function __construct() {
        $setting=Setting::first();
        $user = User::first();
        $notification = $user->unreadNotifications;
        view()->share('notifications', $notification);
        view()->share('setting', $setting);
    }
    public function gallery(){
        $gallery = GalleryPhoto::orderBy('id','Desc')->get();
        $page = 'Gallery';
        $icon = 'gallery.png';
        return view('admin.gallery.gallery',compact('gallery','page','icon'));
    }
    public function store(Request $request)
    {
        $image = $request->file('file');
        $avatarName = $image->getClientOriginalName();
        $destinationPath = 'public/gallery/';
        $rand=rand(1,100);
        $docImage = date('YmdHis') . $rand . "." . $image->getClientOriginalExtension();
        $image->move($destinationPath, $docImage);
        $img=$docImage;
        $gallery = new GalleryPhoto();
        $gallery->photo_name = $avatarName;
        $gallery->photo = $img;
        $gallery->type = $image->getClientOriginalExtension();
        $gallery->status = 1;
        $gallery->save();
        $user = User::where('id',1)->first();
        $log = new Log();
        $log->user_id   = $user->name;
        $log->module    = 'Gallery';
        $log->log       = 'Gallery Image Added';
        $log->save();
    }
    public function addGallery(Request $request)
    {
        // echo '<pre>';
        // print_r($request->All());
        // exit;
        if($request->has('image') && $request->file('image') != null){
            $image = $request->file('image');
            $destinationPath = 'public/gallery/';
            $rand=rand(1,100);
            $docImage = date('YmdHis').$rand. "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $docImage);
            $img=$docImage;
            $type = $image->getClientOriginalExtension();
        }else{
            $img= "";
            $type = "";
        }
        $gallery = new GalleryPhoto();
        $gallery->photo_name = $request->name;
        $gallery->photo = $img;
        $gallery->gallery_type = $request->gallery_type;
        if($request->has('link') && $request->link != ''){
             $gallery->link = $request->link;
        }
        $gallery->type = $type;
        $gallery->status = 1;
        $gallery->save();
        $user = User::where('id',1)->first();
        $log = new Log();
        $log->user_id   = $user->name;
        $log->module    = 'Gallery';
        $log->log       = 'Gallery Image Added';
        $log->save();
        return redirect()->back();
    }
    public function updateGallery(Request $request , $id = NULL)
    {
        // echo '<pre>';
        // print_r($request->All());
        // exit;
       
        $gallery = GalleryPhoto::where('id',$id)->first();
        $gallery->photo_name = $request->name;
         if($request->has('image') && $request->file('image') != null){
            $image = $request->file('image');
            $destinationPath = 'public/gallery/';
            $rand=rand(1,100);
            $docImage = date('YmdHis').$rand. "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $docImage);
            $img=$docImage;
            $type = $image->getClientOriginalExtension();
        }else{
            $img= $req->hidden_image;
            $type = $gallery->type;
        }
        $gallery->photo = $img;
        $gallery->gallery_type = $request->gallery_type;
        if($request->has('link') && $request->link != ''){
             $gallery->link = $request->link;
        }
        $gallery->type = $type;
        $gallery->status = 1;
        $gallery->save();
        $user = User::where('id',1)->first();
        $log = new Log();
        $log->user_id   = $user->name;
        $log->module    = 'Gallery';
        $log->log       = 'Gallery Image Updated';
        $log->save();
        return redirect()->route('admin.gallery');
    }
    public function deleteGallery($id){
        $gallery = GalleryPhoto::where('id',$id);
       	$gallery->delete();
       	$user = User::where('id',1)->first();
        $log = new Log();
        $log->user_id   = $user->name;
        $log->module    = 'Gallery';
        $log->log       = 'Gallery Image Deleted';
        $log->save();
        return 1;
    }
}
