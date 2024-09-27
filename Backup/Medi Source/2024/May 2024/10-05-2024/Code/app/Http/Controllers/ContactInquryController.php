<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactInqury;
use App\Models\Setting;

class ContactInquryController extends Controller
{
    public function __construct() {
        $setting=Setting::first();

        view()->share('setting', $setting);
    }

    public function store(Request $request)
{

    $validatedData = $request->validate([
        'user_type' => 'required',
        'first_name' => 'required',
        'last_name' => 'required',
        'email' => 'required|email',
        'phone' => 'required',
        'clinic_name' => 'required',
        'website' => 'required',
        'number_of_physicians' => 'required|integer',
        'number_of_locations' => 'required|integer',
        'license_number' => 'required|integer',
        'dea_number' => 'required|integer',
        'products_services_interested' => 'required|array',
        'description' => 'required',
    ]);
    $validatedData['products_services_interested'] = json_encode($validatedData['products_services_interested']);

    $contactInqury = new ContactInqury($validatedData);

    $contactInqury->save();
    $name = Auth::guard('web')->user()->first_name . " " . Auth::guard('web')->user()->last_name;
    $log = "Contact Inqury Created by ". $name;
    UserLogHelper::storeWebLog('Inquiry', $log);
    return redirect()->route('contactus')->with('success', 'Inquiry submitted successfully');
}

public function showInquiries()
{
    $inquiries = ContactInqury::all();
    return view('admin.inquiries.index', compact('inquiries'));
}


public function show(ContactInqury $inquiry)
{
    return view('admin.inquiries.show', compact('inquiry'));
}

public function destroy(ContactInqury $inquiry)
{
    $inquiry->delete();
    $name = Auth::guard('admin')->user()->first_name . " " . Auth::guard('admin')->user()->last_name;
    $log = "Contact Inqury Deleted by ". $name;
    UserLogHelper::storeLog('Inquiry', $log);
    return redirect()->route('inquiries.index')->with('success', 'Inquiry deleted successfully');
}

}
