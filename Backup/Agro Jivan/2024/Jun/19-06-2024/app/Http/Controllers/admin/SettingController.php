<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Setting;

class SettingController extends Controller
{
    public function setting(){
        $page = "Setting";
        $setting = Setting::first();
        return view('admin.setting.index',compact('page','setting'));
    }

    public function update(Request $request){
        $id = $request->id;
        $data['login_time']= $request->login_time;
        $data['buffer_time']= $request->buffer_time;
        if(isset($id)){
            
            $where['id']= $id;
            $update = Setting::where($where)->update($data);
            if($update){
                return redirect('admin/setting')->with('success', 'Setting Updated Successfully.');
            }
            return redirect('admin/setting')->with('error', 'Something Went To Wrong.!');
        }
        $insert = Setting::create($data);
        if($insert){
            return redirect('admin/setting')->with('success', 'Setting Updated Successfully.');
        }
        return redirect('admin/setting')->with('error', 'Something Went To Wrong.!');
    }
}
