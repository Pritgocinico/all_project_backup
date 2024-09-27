<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateSettingRequest;
use App\Models\BusinessSetting;
use App\Models\Department;
use App\Models\Log;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SettingController extends Controller
{
    private $setting;
    private $log;
    public function __construct()
    {
        $this->middleware('auth');
        $page = "Setting";
        view()->share('page', $page);
        $this->setting = resolve(Setting::class);
        $this->log = resolve(Log::class)->with('userDetail');
    }
    public function index()
    {
        return view('admin.setting.setting');
    }

    public function settingUpdate(UpdateSettingRequest $request)
    {
        $settingData = $this->setting->first();
        $settingArray = [
            'site_name' => $request->site_name,
            'site_url' => $request->site_url,
            'created_by' => auth()->user()->id,
        ];
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $newFilename = Str::random(40) . '.' . $file->getClientOriginalExtension();
            $pathLogo = $file->storeAs('logo', $newFilename, 'public');
            $settingArray['logo'] = $pathLogo;
        }

        if ($request->hasFile('fa_icon')) {
            $file = $request->file('fa_icon');
            $newFilename = Str::random(40) . '.' . $file->getClientOriginalExtension();
            $faLogoPath = $file->storeAs('fa_icon', $newFilename, 'public');
            $settingArray['fa_icon'] = $faLogoPath;
        }
        $where = ['id' => $settingData->id];
        $update = Setting::updateOrCreate($where,$settingArray);
        $user = User::find(auth()->user()->id);
        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            $newFilename = Str::random(40) . '.' . $file->getClientOriginalExtension();
            $profileLogo = $file->storeAs('profile', $newFilename, 'public');
            $user->profile_image = $profileLogo;
        }
        $user->save();
        if ($update) {
            return redirect()->back()->with('success', 'Setting has been updated successfully.');
        }
        return redirect()->back()->with('error', 'Something went to wrong.');
    }

    public function AllLogs()
    {
        $id = auth()->user()->role_id;
        $logList = $this->log->when($id !== 1, function ($query) {
            $query->where('user_id', Auth()->user()->id);
        })->latest()->paginate(10);
        return view('admin.log.index', compact('logList'));
    }

    public function businessSetting()
    {
        $departmentList = Department::with('businessSettingDetail')->get();
        return view('admin.setting.business_setting', compact('departmentList'));
    }

    public function businessSettingForm($id)
    {
        $depart = Department::where('id', $id)->first();
        $businessSetting = BusinessSetting::where('department_id', $id)->first();
        return view('admin.setting.business_setting_form', compact('depart', 'businessSetting'));
    }

    public function businessSettingUpdate(Request $request, $id)
    {
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $newFilename = Str::random(40) . '.' . $file->getClientOriginalExtension();
            $pathLogo = $file->storeAs('logo', $newFilename, 'public');
        }
        $update = BusinessSetting::updateOrCreate(
            [
                'department_id' => $id,

            ],
            [
                'company_name' => $request->company_name,
                'gst_number' => $request->gst_number,
                'address' => $request->address,
                'description' => $request->description,
                'support_phone' => $request->support_phone,
                'department_id' => $id,
                'logo' => $pathLogo,
            ],
        );
        if ($update) {
            $depart = Department::where('id', $id)->first();
            Log::create([
                'user_id' => Auth()->user()->id,
                'module' => $depart->name . ' Business Setting.',
                'description' => auth()->user()->name . " Updated a Business Setting named '" . $request->company_name . "'"
            ]);
            return redirect()->route('business-setting')->with('success', 'Business Setting updated successfully.');
        }
        return redirect()->route('business.setting.view', $id)->with('error', 'Something went wrong.');
    }
}
