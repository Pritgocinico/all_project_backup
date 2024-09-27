<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateSettingRequest;
use App\Models\Log;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    private $setting;
    private $log;
    public function __construct()
    {
        $this->middleware('auth');

        $this->setting = resolve(Setting::class);
        $this->log = resolve(Log::class)->with('userDetail');
    }
    public function index()
    {
        return view('admin.setting.setting');
    }

    public function settingUpdate(UpdateSettingRequest $request)
    {
        $setting = Setting::first();
        if ($setting) {
            $setting->site_name = $request->site_name;
            $setting->site_url = $request->site_url;
            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                $newFilename = Str::random(40) . '.' . $file->getClientOriginalExtension();
                $pathLogo = $file->storeAs('logo', $newFilename, 'public');
                $setting->logo = $pathLogo;
            }

            if ($request->hasFile('fa_icon')) {
                $file = $request->file('fa_icon');
                $newFilename = Str::random(40) . '.' . $file->getClientOriginalExtension();
                $faLogoPath = $file->storeAs('fa_icon', $newFilename, 'public');
                $setting->fa_icon = $faLogoPath;
            }
            $user = User::find(auth()->user()->id);
            if ($request->hasFile('profile_image')) {
                $file = $request->file('profile_image');
                $newFilename = Str::random(40) . '.' . $file->getClientOriginalExtension();
                $profileLogo = $file->storeAs('profile', $newFilename, 'public');
                $user->profile_image = $profileLogo;
            }
            $user->save();
            $update = $setting->save();
            if($update){
                return redirect()->back()->with('success','Setting has been updated successfully.');
            }
            return redirect()->back()->with('error','Something went to wrong.');
        } 
        $setting = new Setting();
        $setting->site_name = $request->site_name;
        $setting->site_url = $request->site_url;
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $newFilename = Str::random(40) . '.' . $file->getClientOriginalExtension();
            $pathLogo = $file->storeAs('logo', $newFilename, 'public');
            $setting->logo = $pathLogo;
        }

        if ($request->hasFile('fa_icon')) {
            $file = $request->file('fa_icon');
            $newFilename = Str::random(40) . '.' . $file->getClientOriginalExtension();
            $faLogoPath = $file->storeAs('fa_icon', $newFilename, 'public');
            $setting->fa_icon = $faLogoPath;
        }
        $update = $setting->save();
        if($update){
            Log::create([
                'user_id' => auth()->user()->id,
                'module' => 'Setting',
                'description' => auth()->user()->name . " Updated Successfully."
            ]);
            return redirect()->back()->with('success','Setting has been updated successfully.');
        }
        return redirect()->back()->with('error','Something went to wrong.');
    }

    public function AllLogs(){
        $id = auth()->user()->role_id;
        $logList = $this->log->when($id !== 1,function($query){
            $query->where('user_id',Auth()->user()->id);
        })->latest()->paginate(10);
        return view('admin.log.index',compact('logList'));
    }
}
