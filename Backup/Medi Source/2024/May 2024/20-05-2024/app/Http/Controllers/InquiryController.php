<?php

namespace App\Http\Controllers;
use App\Models\Setting;
use App\Models\Inquiry;
use Mail;
use Illuminate\Http\Request;

class InquiryController extends Controller
{
    public function __construct() {
        $setting=Setting::first();

        view()->share('setting', $setting);
    }



    public function submitForm(Request $request)
    {
        // $rules = [
        //     'name' => 'required|string|max:255',
        //     'contact' => 'required|max:20',
        //     'email' => 'required|email|max:255',
        //     'state' => 'required|max:255',
        //     'message' => 'required',
        // ];
        // $request->validate($rules);
        $inquiry = new Inquiry([
            'name' => $request->input('name'),
            'contact' => $request->input('contact'),
            'email' => $request->input('email'),
            'state' => $request->input('state'),
            'message' => $request->input('message'),
            'consent' => $request->has('consent') ? 1 : 0,
        ]);
        $inquiry->save();
        if($inquiry){
            return response()->json(['success' => 'Form submitted successfully!'], 200);
        }else{
            return response()->json(['success' => 'Something went wrong!'], 200);
        }
    }
    public function index()
    {
        $inquiries = Inquiry::all();

        return view('admin.homeinquiries.index', ['inquiries' => $inquiries]);
    }
    public function destroy($id)
    {
        $inquiry = Inquiry::find($id);

        if ($inquiry) {
            $inquiry->delete();
            return redirect()->route('homeinquiries.index')->with('success', 'Inquiry deleted successfully!');
        } else {
            return redirect()->route('homeinquiries.index')->with('error', 'Inquiry not found!');
        }
    }
    public function show($id)
    {
        $inquiry = Inquiry::find($id);
        if ($inquiry) {
            return view('admin.homeinquiries.show', ['inquiry' => $inquiry]);
        } else {
            return redirect()->route('homeinquiries.index')->with('error', 'Inquiry not found!');
        }
    }
}
