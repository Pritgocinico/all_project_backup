<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\HelpInquiry;
use App\Models\Setting;
class HelpInquiriesController extends Controller
{
    public function __construct() {
        $setting=Setting::first();
        view()->share('setting', $setting);
    }
    public function submitForm(Request $request)
    {
        // Validate the form data
        $validatedData = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'nullable|string',
            'message' => 'required|string',
            // 'team_member_contact' => 'nullable|string',
            // 'sign_up_for_email_list' => 'boolean',
        ]);
        // Convert checkbox value to 1 or 0
        $validatedData['sign_up_for_email_list'] = $request->has('sign_up_for_email_list') ? 1 : 0;
        // Create a new HelpInquiry instance and save it to the database
        HelpInquiry::create($validatedData);
        // Additional logic or redirect here
        return redirect()->back()->with('success', 'Form submitted successfully!');
    }
    public function index()
    {
        $inquiries = HelpInquiry::all(); // Adjust as needed
        return view('admin.helpinquiries.index', compact('inquiries'));
    }
    public function show($id)
    {
        $inquiry = HelpInquiry::findOrFail($id);
        return view('admin.helpinquiries.show', compact('inquiry'));
    }
    public function destroy($id)
    {
        $inquiry = HelpInquiry::findOrFail($id);
        $inquiry->delete();
        return redirect()->route('helpinquiries.index')->with('success', 'Inquiry deleted successfully');
    }
}
