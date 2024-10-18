<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePushLeadRequest;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Log;
use App\Models\Lead;
use App\Models\User;
use App\Models\LeadMember;
use Illuminate\Support\Facades\Validator;
use App\Helpers\UtilityHelper;
use App\Models\CustAddress;
use App\Models\CustAlternateNumber;
use App\Models\Department;
use App\Models\LeadAttachment;
use App\Models\LeadTravelDetail;
use App\Models\Setting;
use App\Models\Disease;
use App\Models\Product;
use Carbon\Carbon;
use PDF;
use Illuminate\Support\Str;
use Pusher\Pusher;
use Notification;
use App\Notifications\SendNotification;

class PushLeadController extends Controller
{
    private $lead;
    public function __construct()
    {
        $page = "Push Leads";
        $this->lead = resolve(Lead::class)->with('customerDetail', 'leadAttachment', 'leadTravelDetail', 'userDetail', 'leadMemberDetail', 'leadMemberDetail.userDetail');
        view()->share('page', $page);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $type = request('type');
        return view('admin.push_lead.index', compact('type'));
    }

    public function pushleadAjaxList(Request $request)
    {
        $search = $request->search;
        $leads = $this->lead
            ->when(Auth()->user()->role_id !== "1", function ($query) {
                $query->where(function ($query) {
                    $query->where('created_by', Auth()->user()->id)
                        ->orWhereHas('leadMemberDetail', function ($query) {
                            $query->where('user_id', Auth()->user()->id);
                        });
                });
            })
            ->when($search, function ($query, $search) {
                return $query->where(function ($query) use ($search) {
                    $query->where('lead_id', 'like', '%' . $search . '%')
                        ->orWhere('created_at', 'like', '%' . $search . '%')
                        ->orWhereHas('customerDetail', function ($query) use ($search) {
                            $query->where('customers.name', 'like', '%' . $search . '%');
                        })->orWhereHas('userDetail', function ($query) use ($search) {
                            $query->where('name', 'like', '%' . $search . '%');
                        });
                });
            })
            ->latest()
            ->get();
        return view('admin.push_lead.ajax_list', compact('leads'));
    }

    public function pushexportFile(Request $request)
    {
        $search = $request->search;
        $type = $request->type;

        $leads = $this->lead->when($type == "assign", function ($query) {
            $query->WhereHas('leadMemberDetail', function ($query) {
                $query->where('user_id', Auth()->user()->id);
            });
        })->when($type == "create", function ($query) {
            $query->where('created_by', Auth()->user()->id);
        })->when(Auth()->user()->role_id !== "1", function ($query) {
            $query->where(function ($query) {
                $query->where('created_by', Auth()->user()->id)
                    ->orWhereHas('leadMemberDetail', function ($query) {
                        $query->where('user_id', Auth()->user()->id);
                    });
            });
        })
            ->when($search, function ($query, $search) {
                return $query->where(function ($query) use ($search) {
                    $query->where('lead_id', 'like', '%' . $search . '%')
                        ->orWhere('created_at', 'like', '%' . $search . '%')
                        ->orWhere('lead_amount', 'like', '%' . $search . '%')
                        ->orWhereHas('customerDetail', function ($query) use ($search) {
                            $query->where('customers.name', 'like', '%' . $search . '%');
                        })->orWhereHas('userDetail', function ($query) use ($search) {
                            $query->where('name', 'like', '%' . $search . '%');
                        });
                });
            })->latest()
            ->get();

        if ($request->format == "csv" || $request->format == "excel") {
            $headers = array(
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=lead Export.csv",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            );

            $columns = array('Lead', 'Customer Name', 'Lead Amount', 'Status', 'Created By', 'Created At');
            $callback = function () use ($leads, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
                foreach ($leads as $lead) {
                    $date = "";
                    if (isset($lead->created_at)) {
                        $date = UtilityHelper::convertDmyAMPMFormat($lead->created_at);
                    }
                    if ($lead->lead_status == 1) {
                        $status = "Pending Lead";
                    } elseif ($lead->lead_status == 2) {
                        $status = "Assigned Lead";
                    } elseif ($lead->lead_status == 3) {
                        $status = "Hold Lead";
                    } elseif ($lead->lead_status == 4) {
                        $status = "Complete Lead";
                    } elseif ($lead->lead_status == 5) {
                        $status = "Extends Lead";
                    } elseif ($lead->lead_status == 6) {
                        $status = "Cancel Lead";
                    }
                    $name = isset($lead->userDetail) ? $lead->userDetail->name : "-";
                    $customer = isset($lead->customerDetail) ? $lead->customerDetail->name : '-';
                    fputcsv($file, array($lead->lead_id, $customer, $lead->lead_amount, $status, $name, $date));
                }
                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        }
        if ($request->format == "pdf") {
            $setting = Setting::first();
            $pdf = PDF::loadView('admin.pdf.leads', ['leads' => $leads,'setting' =>$setting]);
            return $pdf->download('Lead.pdf');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $lastId =  Customer::all()->last() ? Customer::all()->last()->id + 1 : 1;
        $customerId = 'SA-CUS-' . substr("00000{$lastId}", -6);
        $customers = Customer::get();
        $diseases = Disease::get();
        $employees = User::where('role_id', "!=" , '1')->get();
        $products = Product::get();
        return view('admin.push_lead.create', compact('customers', 'customerId', 'diseases', 'employees', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreatePushLeadRequest $request)
    {
        $lastId =  Lead::all()->last() ? Lead::all()->last()->id + 1 : 1;
        $leadID = 'SA-LEAD-' . substr("00000{$lastId}", -6);
        $lead = new Lead;
        $lead->lead_id = $leadID;
        $lead->customer_id = $request->customer_id;
        if($request->name){
            // dd($request->name);
            $cuslastId =  Customer::all()->last() ? Customer::all()->last()->id + 1 : 1;
            $customerId = 'SA-CUS-' . substr("00000{$cuslastId}", -6);
            $customer = new Customer();
            $customer->name = ucwords(strtolower($request->name));
            $customer->customer_id = $customerId;
            $customer->mobile_number = $request->customer_number;
            $customer->cust_disease = $request->disease;
            $customer->created_by = Auth()->user()->id;
            $customer->role_id = 3;
            $customer->save();
            $lead->customer_id = $customer->id;
            $lead->customer_type = 'New Lead';
        }
        $lead->assigned_to = $request->assigned_to;
        $lead->lead_date = Carbon::now();
        $lead->created_by = Auth()->user()->id;
        $lead->customer_type = 'Resale Lead';
        $insert = $lead->save();
        if ($insert) {
            Log::create([
                'user_id' => Auth()->user()->id,
                'module' => 'Lead',
                'description' => auth()->user()->name . " Created a Lead named '" . $leadID . "'"
            ]);
            return redirect()->route('pushleads.index')->with('success', 'Lead has been created successfully.');
        }
        return redirect()->route('pushleads.create')->with('error', 'Something went wrong.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $lead = $this->lead->with('customerDetail.getAlternativeNumber', 'customerDetail.customerAddress','productDetail.leadProduct')->find($id);
        $customer = $lead->customerDetail;
        $custAltnumbers = $customer->getAlternativeNumber;
        $custAddresses = $customer->customerAddress;
        return view('admin.push_lead.show', compact('lead', 'customer', 'custAltnumbers', 'custAddresses'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $lead = $this->lead->find($id);
        $customers = Customer::get();
        $diseases = Disease::get();
        $employees = User::where('role_id', "!=" , '1')->get();
        return view('admin.push_lead.edit', compact('lead','customers','diseases', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreatePushLeadRequest $request, string $id)
    {
        $lead = $this->lead->find($id);
        $lead->customer_id = $request->customer_id;
        if($request->name){
            $cuslastId =  Customer::all()->last() ? Customer::all()->last()->id + 1 : 1;
            $customerId = 'SA-CUS-' . substr("00000{$cuslastId}", -6);
            $customer = new Customer();
            $customer->name = ucwords(strtolower($request->name));
            $customer->customer_id = $customerId;
            $customer->mobile_number = $request->customer_number;
            $customer->cust_disease = $request->disease;
            $customer->created_by = Auth()->user()->id;
            $customer->role_id = 3;
            $customer->save();
            $lead->customer_id = $customer->id;
            $lead->customer_type = 'New Lead';
        }    
        $lead->assigned_to = $request->assigned_to;
        $lead->created_by = Auth()->user()->id;
        $insert = $lead->save();
        if ($insert) {
            Log::create([
                'user_id' => Auth()->user()->id,
                'module' => 'Lead',
                'description' => auth()->user()->name . " Updated a Lead named '" . $lead->lead_id . "'"
            ]);
            return redirect()->route('pushleads.index')->with('success', 'Lead has been Updated successfully.');
        }
        return redirect()->route('pushleads.create')->with('error', 'Something went wrong.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $lead = $this->lead->find($id);
        $delete = $lead->delete();
        if ($delete) {
            Log::create([
                'user_id' => Auth()->user()->id,
                'module' => 'Lead',
                'description' => auth()->user()->name . " Deleted Lead named '" . $lead->lead_id . "'"
            ]);
            return response()->json(['status' => 1, 'message' => 'Lead has been deleted successfully.'], 200);
        }
        return response()->json(['status' => 0, 'message' => 'Something went to wrong.'], 500);
    }
}
