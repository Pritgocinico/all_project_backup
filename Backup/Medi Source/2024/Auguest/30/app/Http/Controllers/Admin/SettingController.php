<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Nnjeim\World\World;
use App\Models\Setting;



class SettingController extends Controller
{
    public function __construct() {
        $setting=Setting::first();

        view()->share('setting', $setting);
    }


    public function index()
    {
        $settings = Setting::first(); // Assuming you only have one row of general settings

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string',
            'site_url' => 'required',
            'date_format' => 'required',
        ]);

        // Get the first row from the settings table or create a new one if it doesn't exist
        $settings = Setting::firstOrNew();

        // Update the fields with the request data
        $settings->fill($request->only([
            'company_name',
            'site_url',
            'date_format',
        ]));

        // Handle file uploads if necessary
        if ($request->hasFile('logo')) {
            // Handle logo file upload and update the 'logo' field
            if($request->has('logo') && $request->file('logo') != null){
                $image = $request->file('logo');
                $destinationPath = 'public/storage/logos';
                $rand=rand(1,100);
                $docImage = date('YmdHis'). $rand."." . $image->getClientOriginalExtension();
                $image->move($destinationPath, $docImage);
                $img=$docImage;
                $logoPath = 'logos/'.$img;
                $settings->logo = $logoPath;
            }
        }

        if ($request->hasFile('favicon')) {
            // Handle favicon file upload and update the 'favicon' field
            // $faviconPath = $request->file('favicon')->store('favicons', 'public');
            if($request->has('favicon') && $request->file('favicon') != null){
                $image = $request->file('favicon');
                $destinationPath = 'public/storage/favicon';
                $rand=rand(1,100);
                $docImage = date('YmdHis'). $rand."." . $image->getClientOriginalExtension();
                $image->move($destinationPath, $docImage);
                $img=$docImage;
                $faviconPath = 'favicon/'.$img;
                $settings->favicon = $faviconPath;
            }
            // $settings->favicon = $faviconPath;
        }

        // Save the changes to the database
        $settings->save();

        return redirect()->route('admin.settings')->with('success', 'Settings updated successfully');
    }


    public function company()
    {
        // Fetch cities in Gujarat (replace 'IN' with the ISO2 code for India)
        $actionCities = World::cities([
            'filters' => [
              'country_code' => 'IN',
                'state_code' => 'GJ',
            ],
        ]);

        if ($actionCities->success) {
            $cities = $actionCities->data;
        }

        // Fetch states in India
        $actionStates = World::states([
            'filters' => [
                'country_code' => 'IN',
            ],
        ]);

        if ($actionStates->success) {
            $states = $actionStates->data;
        }

        return view('admin.settings.companysetting', compact('cities', 'actionCities', 'states', 'actionStates'));
    }

    public function saveCompany(Request $request)
    {
        $request->validate([
            'address' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'postal_code' => 'required|string',
            'phone' => 'required|string',
            // 'gst_number' => 'required|string',
        ]);

        // Retrieve the existing company setting or create a new one if not found
        $companySetting = Setting::firstOrNew();

        // Update the fields with the request data
        $companySetting->address = $request->input('address');
        $companySetting->city = $request->input('city');
        $companySetting->state = $request->input('state');
        $companySetting->postal_code = $request->input('postal_code');
        $companySetting->phone = $request->input('phone');
        // $companySetting->gst_number = $request->input('gst_number');

        // Save the changes to the database
        $companySetting->save();

        return redirect()->route('admin.settings.companysetting')->with('success', 'Company settings updated successfully');
    }



    // Example method to fetch cities (replace this with your actual logic)
    private function getCities()
    {
        return [
            'City A',
            'City B',
            'City C',
            // Add more cities as needed
        ];
    }

    public function email()
    {
        $emailSetting = Setting::firstOrNew();
        return view('admin.settings.emailsetting', compact('emailSetting'));
    }

    public function saveEmail(Request $request)
    {

        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Assuming you have a model for your settings
        $emailSetting = Setting::firstOrNew(); // Assuming you only have one row of general settings
        $emailSetting->email = $request->input('email');
        // If you want to encrypt the password, you can use bcrypt() method
        $emailSetting->password = bcrypt($request->input('password'));
        $emailSetting->save();

        return redirect()->route('admin.settings.email')->with('success', 'Email settings saved successfully');
    }
}
