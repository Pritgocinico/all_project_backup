<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateLeadRequest;
use App\Models\Country;
use App\Models\Customer;
use App\Models\Log;
use App\Models\Lead;
use App\Models\User;
use App\Models\LeadMember;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Helpers\UtilityHelper;
use App\Models\CustAddress;
use App\Models\CustomerProduct;
use App\Models\CustAlternateNumber;
use App\Models\Department;
use App\Models\LeadAttachment;
use App\Models\LeadTravelDetail;
use App\Models\Setting;
use App\Models\Disease;
use App\Models\Product;
use App\Models\Remark;
use Carbon\Carbon;
use PDF;
use Illuminate\Support\Str;
use Pusher\Pusher;
use Notification;
use App\Notifications\SendNotification;

class LeadController extends Controller
{
    private $lead;
    public function __construct()
    {
        $page = "Leads";
        $this->lead = resolve(Lead::class)->with('customerDetail', 'leadAttachment', 'leadTravelDetail', 'userDetail', 'leadMemberDetail', 'leadMemberDetail.userDetail');
        view()->share('page', $page);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $type = request('type');
        return view('admin.lead.index', compact('type'));
    }

    public function leadAjaxList(Request $request)
    {
        $search = $request->search;
        $leads = $this->lead
            // ->when(Auth()->user()->role_id !== "1", function ($query) {
            //     $query->where(function ($query) {
            //         $query->where('created_by', Auth()->user()->id)
            //             ->orWhereHas('leadMemberDetail', function ($query) {
            //                 $query->where('assigned_to', Auth()->user()->id);
            //             });
            //     });
            // })
            ->when((int) Auth()->user()->role_id != 1, function ($query) {
                $query->where(function ($query) {
                    $query->where('assigned_to', Auth()->user()->id);
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
        // dd($leads);
        return view('admin.lead.ajax_list', compact('leads'));
    }

    public function exportFile(Request $request)
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
            $pdf = PDF::loadView('admin.pdf.leads', ['leads' => $leads, 'setting' => $setting]);
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
        $employees = User::where('role_id', "!=", '1')->get();
        $products = Product::get();
        return view('admin.lead.create', compact('customers', 'customerId', 'diseases', 'employees', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateLeadRequest $request)
    {
        $lastId =  Lead::all()->last() ? Lead::all()->last()->id + 1 : 1;
        $leadID = 'SA-LEAD-' . substr("00000{$lastId}", -6);
        $lead = new Lead;
        $lead->lead_id = $leadID;
        $number = $request->mobile_number;
        $customer = Customer::where('mobile_number', $number)->orWhereHas('getAlternativeNumber', function ($query) use ($number) {
            $query->where('cust_alt_num', $number);
        });
        if ($customer->exists()) {
            $lead->customer_id = $customer->first()->id;
        } else {
            $customer = new Customer();
            $customer->name = ucwords(strtolower($request->name));
            $cusLastId =  Customer::all()->last() ? Customer::all()->last()->id + 1 : 1;
            $customerId = 'SA-CUS-' . substr("00000{$cusLastId}", -6);
            $customer->customer_id = $customerId;
            $customer->mobile_number = $request->mobile_number;
            $disease = $request->cust_disease;
            $customer->cust_disease = $request->cust_disease;
            if($disease == "other"){
                $diseases = new Disease();
                $diseases->name = $request->other_cust_disease;
                $diseases->created_by = Auth()->user()->id;
                $diseases->save();
                
                $customer->cust_disease = $diseases->id;                
            }
            $insert = $customer->save();
            $lead->customer_id = $customer->id;
        }
        $lead->created_by = Auth()->user()->id;
        $lead->lead_source = $request->lead_source;
        $lead->other_lead_source = $request->other_lead_source;
        $lead->customer_type = $request->lead_type;
        $lead->customer_language = $request->customer_language;
        $lead->reference_name = $request->reference_name;
        $lead->problem_duration = $request->problem_duration;
        $lead->for_whom = $request->for_whom;
        $lead->send_whatsapp = $request->send_whatsapp;
        $lead->lead_date = $request->lead_date;
        $lead->assigned_to = $request->assigned_to;
        $insert = $lead->save();
        if ($insert) {
            if ($request->product !== null) {
                foreach ($request->product as $key => $pro) {
                    $custProduct = new CustomerProduct();
                    $custProduct->product_id = $pro;
                    $custProduct->customer_id = $lead->customer_id;
                    $custProduct->lead_id = $lead->id;
                    $custProduct->save();
                }
            }
            Log::create([
                'user_id' => Auth()->user()->id,
                'module' => 'Lead',
                'description' => auth()->user()->name . " Created a Lead named '" . $leadID . "'"
            ]);
            return redirect()->route('leads.index')->with('success', 'Lead has been created successfully.');
        }
        return redirect()->route('leads.create')->with('error', 'Something went wrong.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $lead = $this->lead->with('customerDetail.getAlternativeNumber', 'customerDetail.customerAddress', 'customerDetail.custDisease', 'productDetail.leadProduct')->find($id);
        $customer = $lead->customerDetail;
        $products = Product::get();
        $custAltnumbers = $customer->getAlternativeNumber;
        $custAddresses = $customer->customerAddress;
        $leadRemarks = Remark::with('userDetail','userDetail.departmentDetail')->where('lead_id', $id)->get();
        $diseases = Disease::get();
        $leadProduct = CustomerProduct::where('lead_id', $id)->where('customer_id', $customer->id)->pluck('product_id')->toArray();
        // if (Auth()->user()->role_id == 1) {
        //     return view('admin.lead.show', compact('leadProduct', 'products', 'diseases', 'lead', 'customer', 'custAltnumbers', 'custAddresses', 'leadRemarks'));
        // }
        return view('admin.lead.show', compact('leadProduct', 'products', 'diseases', 'lead', 'customer', 'custAltnumbers', 'custAddresses', 'leadRemarks'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $lastId =  Customer::all()->last() ? Customer::all()->last()->id + 1 : 1;
        $customerId = 'SA-CUS-' . substr("00000{$lastId}", -6);
        $lead = $this->lead->find($id);
        $custProducts = CustomerProduct::where('lead_id', $id)->pluck('product_id')->toArray();
        $customers = Customer::get();
        $departmentList = Department::get();
        $diseases = Disease::get();
        $employees = User::where('role_id', "!=", '1')->get();
        $products = Product::get();
        return view('admin.lead.edit', compact('customerId', 'departmentList', 'lead', 'customers', 'custProducts', 'diseases', 'employees', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateLeadRequest $request, string $id)
    {
        $lead = $this->lead->find($id);
        // dd($request->all(), $id, $lead);
        $lead->customer_id = $request->customer_id;
        $lead->updated_by = Auth()->user()->id;
        $lead->lead_source = $request->lead_source;
        $lead->customer_type = $request->lead_type;
        $lead->reference_name = $request->reference_name;
        $lead->other_lead_source = $request->other_lead_source;
        $lead->customer_language = $request->customer_language;
        $lead->problem_duration = $request->problem_duration;
        $lead->for_whom = $request->for_whom;
        $lead->send_whatsapp = $request->send_whatsapp;
        $lead->lead_date = $request->lead_date;
        $lead->assigned_to = $request->assigned_to;
        $update = $lead->save();
        if ($update) {
            if ($request->product !== null) {
                CustomerProduct::where('lead_id', $id)->delete();
                foreach ($request->product as $key => $pro) {
                    $custProduct = new CustomerProduct();
                    $custProduct->product_id = $pro;
                    $custProduct->customer_id = $lead->customer_id;
                    $custProduct->lead_id = $lead->id;
                    $custProduct->save();
                }
            }
            Log::create([
                'user_id' => Auth()->user()->id,
                'module' => 'Lead',
                'description' => auth()->user()->name . " Updated a Lead named '" . $lead->lead_id . "'"
            ]);
            return redirect()->route('leads.index')->with('success', 'Lead has been updated successfully.');
        }
        return redirect()->route('leads.edit')->with('error', 'Something went wrong.');
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

    public function addCustomerForm(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'mobile_number' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'message' =>  $validator->errors()->first()], 404);
        }
        $lastId =  Customer::all()->last() ? Customer::all()->last()->id + 1 : 1;
        $customerId = 'CA-CUS-' . substr("00000{$lastId}", -6);
        $customer = new Customer();
        $customer->insurance = $request->insurance;
        $customer->insurance_type = $request->insurance_type;
        $customer->customer_department = $request->customer_department;
        $customer->name = $request->name;
        $customer->customer_id = $customerId;
        $customer->email = $request->email;
        $customer->mobile_number = $request->mobile_number;
        $customer->birth_date = $request->birth_date;
        $customer->pan_card_number = $request->pan_card_number;
        $customer->aadhar_number = $request->aadhar_number;
        $customer->service_preference = $request->service_preference;
        $customer->reference = $request->reference;
        $customer->address = $request->address;
        $customer->city = $request->city;
        $customer->state = $request->state;
        $customer->country = $request->country;
        $customer->pin_code = $request->pin_code;
        $customer->passport_number = $request->passport_number;
        $customer->created_by = Auth()->user()->id;
        $customer->role_id = 3;
        if ($request->hasFile('gst_certificate')) {
            $file = $request->file('gst_certificate');
            $newFilename = Str::random(40) . '.' . $file->getClientOriginalExtension();
            $gstCertificatePath = $file->storeAs('customer/gst_certificate', $newFilename, 'public');
            $customer->gst_certificate = $gstCertificatePath;
        }
        if ($request->hasFile('aadhar_card_file')) {
            $file = $request->file('aadhar_card_file');
            $newFilename = Str::random(40) . '.' . $file->getClientOriginalExtension();
            $aadharCardFilePath = $file->storeAs('customer/aadhar_card', $newFilename, 'public');
            $customer->aadhar_card_file = $aadharCardFilePath;
        }
        if ($request->hasFile('pan_card_file')) {
            $file = $request->file('pan_card_file');
            $newFilename = Str::random(40) . '.' . $file->getClientOriginalExtension();
            $panCardPath = $file->storeAs('customer/pan_card', $newFilename, 'public');
            $customer->pan_card_file = $panCardPath;
        }
        if ($request->hasFile('passport_file')) {
            $file = $request->file('passport_file');
            $passportFileName = Str::random(40) . '.' . $file->getClientOriginalExtension();
            $passportFilePath = $file->storeAs('customer/passport', $passportFileName, 'public');
            $customer->passport_file = $passportFilePath;
        }
        $insert = $customer->save();
        if ($insert) {
            Log::create([
                'user_id' => auth()->user()->id,
                'module' => 'Customer',
                'description' => auth()->user()->name . " created a Customer named '" . $request->name . "'"
            ]);
            return response()->json(['status' => 1, 'data' => $customer, 'message' => 'Customer has been created successfully.'], 200);
        }
        return response()->json(['status' => 1, 'message' => 'Something went to wrong.'], 500);
    }

    public function leadStatusChange(Request $request)
    {
        $rules = [
            'lead_id' => 'required|exists:leads,id',
            'status' => 'required',
        ];
        if ($request->status != 1) {
            $rules['remarks'] = 'required';
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['status' => 0, 'message' =>  $validator->errors()->first()], 422);
        }
        $lead = Lead::where('id', $request->lead_id)->first();
        $lead->lead_status = $request->status;
        $statusName = "Pending";
        if ($request->status == 2) {
            $statusName = "In Process";
            $lead->lead_in_process_remarks = $request->remarks;
        }
        if ($request->status == 3) {
            $statusName = "Complete";
            $lead->lead_complete_remarks = $request->remarks;
            $lead->lead_complete_date_time = Carbon::now();
        }
        if ($request->status == 4) {
            $statusName = "Cancel";
            $lead->lead_cancel_remarks = $request->remarks;
            $lead->lead_cancel_date_time = Carbon::now();
        }
        $update = $lead->save();
        if ($update) {
            Log::create([
                'user_id' => auth()->user()->id,
                'module' => 'Lead',
                'description' => auth()->user()->name . " Update the status is " . $statusName . " of Lead '" . $lead->lead_id . "'"
            ]);
            $config = config('services')['pusher'];
            $pusher = new Pusher($config['key'],  $config['secret'], $config['app_id'], [
                'cluster' => env('PUSHER_APP_CLUSTER', 'ap2'),
            ]);
            $offerData = [
                'user_id' => $lead->created_by,
                'type' => 'Lead',
                'text' => 'Lead Status Change',
                'title' => auth()->user()->name . " Update the status is " . $statusName . " of Lead '" . $lead->lead_id . "'",
                'url' => route('leads.index'),
            ];
            $user = User::where('id', $lead->created_by)->first();
            Notification::send($user, new SendNotification($offerData));
            $pusher->trigger('notifications', 'new-notification', $offerData);
            return response()->json(['status' => 1, 'data' => $lead, 'message' => 'Lead has been updated successfully.'], 200);
        }
        return response()->json(['status' => 1, 'message' => 'Something went to wrong.'], 500);
    }

    public function leadRemarks(Request $request)
    {
        $lead_remark = new Remark();
        $lead_remark->lead_id = $request->lead_id;
        $lead_remark->title = $request->title;
        $lead_remark->other_title = $request->other_title;
        $lead_remark->remark = $request->remark;
        $lead_remark->created_by = Auth()->user()->id;
        $lead = $this->lead->find($request->lead_id);

        $store = $lead_remark->save();
        if ($store) {
            $leadRemarks = Remark::with('userDetail','userDetail.departmentDetail')->where('id', $lead_remark->id)->first();
            Log::create([
                'user_id' => auth()->user()->id,
                'module' => 'Lead',
                'description' => auth()->user()->name . " Added Remark on Lead '" . $lead->lead_id . "'"
            ]);
            return response()->json(['status' => 1, 'data' => $leadRemarks, 'message' => 'Remark added successfully.'], 200);
        }
        return response()->json(['status' => 1, 'message' => 'Something went to wrong.'], 500);
    }
    public function updateLeadCustomerData(Request $request)
    {
        $lead = $this->lead->find($request->lead_id);
        if ($lead) {
            // $lead->lead_source = $request->lead_source;
            $lead->other_lead_source = $request->other_lead_source;
            $lead->lead_date = $request->lead_date;
            $lead->customer_language = $request->customer_language;
            $lead->problem_duration = $request->problem_duration;
            $lead->for_whom = $request->for_whom;
            // $lead->other_lead_source = $request->other_lead_source;
            $lead->save();
            $customer = Customer::find($request->customer_id);
            if ($customer) {
                $customer->cust_age = $request->age;
                $customer->cust_height = $request->height;
                $customer->cust_weight = $request->weight;
                $customer->wa_exist = $request->wa_exist;
                $disease = $request->disease;
                $customer->cust_disease = $disease;
                if($disease == "other"){
                    $diseases = new Disease();
                    $diseases->name = $request->other_disease;
                    $diseases->created_by = Auth()->user()->id;
                    $diseases->save();
                    $customer->cust_disease = $diseases->id;
                }
                $customer->save();
                if ($request->product !== null) {
                    foreach ($request->product as $key => $pro) {
                        $customerProduct = CustomerProduct::where('lead_id', $lead->id)->where('product_id', $pro)->first();
                        if (!isset($customerProduct)) {
                            $custProduct = new CustomerProduct();
                            $custProduct->product_id = $pro;
                            $custProduct->customer_id = $lead->customer_id;
                            $custProduct->lead_id = $lead->id;
                            $custProduct->save();
                        }
                    }
                }
                if ($request->alt_num_id !== null) {
                    foreach ($request->alt_num_id as $key => $numberId) {
                        if ($request->cust_alt_num[$key] !== null) {
                            $number = $request->cust_alt_num[$key];
                            $customer = Customer::where('mobile_number', $number)->orWhereHas('getAlternativeNumber', function ($query) use ($number) {
                                $query->where('cust_alt_num', $number);
                            });
                            if (!$customer->exists()) {
                                $altNumberExist = CustAlternateNumber::where('id', $numberId)->first();
                                if (!isset($altNumberExist)) {
                                    $altNumberExist = new CustAlternateNumber();
                                }
                                $altNumberExist->customer_id = $request->customer_id;
                                $altNumberExist->cust_alt_num = $request->cust_alt_num[$key];
                                $altNumberExist->alt_wa_exist = $request->alt_wa_exist[$key];
                                $altNumberExist->save();
                            }
                        }
                    }
                }
                if ($request->address_id !== null) {
                    foreach ($request->address_id as $key => $addressId) {
                        if ($addressId !== null) {

                            $addressExist = CustAddress::where('id', $addressId)->first();
                            if (!isset($addressExist)) {
                                $addressExist = new CustAddress();
                            }
                            $addressExist->customer_id = $request->customer_id;
                            $addressExist->add_type = $request->add_type[$key];
                            $addressExist->pin_code = $request->pin_code[$key];
                            $addressExist->address = $request->address[$key];
                            $addressExist->village = $request->village[$key];
                            $addressExist->office_name = $request->office_name[$key];
                            $addressExist->dist_city = $request->dist_city[$key];
                            $addressExist->dist_state = $request->dist_state[$key];
                            $addressExist->save();
                        }
                    }
                }
            }
            Log::create([
                'user_id' => auth()->user()->id,
                'module' => 'Lead',
                'description' => auth()->user()->name . " Updated Lead Customer Details on Lead '" . $lead->lead_id . "'"
            ]);
            return redirect()->route('leads.index')->with('success', 'Lead Customer Details updated successfully.');
        }
        return redirect()->back()->with('error', 'Something went to wrong.');
    }
    public function viewEdit($id){
        $lead = $this->lead->with('customerDetail.getAlternativeNumber', 'customerDetail.customerAddress', 'customerDetail.custDisease', 'productDetail.leadProduct')->find($id);
        $customer = $lead->customerDetail;
        $products = Product::get();
        $custAltnumbers = $customer->getAlternativeNumber;
        $custAddresses = $customer->customerAddress;
        $leadRemarks = Remark::with('userDetail','userDetail.departmentDetail')->where('lead_id', $id)->get();
        $diseases = Disease::get();
        $leadProduct = CustomerProduct::where('lead_id', $id)->where('customer_id', $customer->id)->pluck('product_id')->toArray();
        return view('admin.lead.show_edit', compact('leadProduct', 'products', 'diseases', 'lead', 'customer', 'custAltnumbers', 'custAddresses', 'leadRemarks'));
    }
}
