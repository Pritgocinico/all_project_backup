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
use Hash;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    private $lead;
    public function __construct()
    {
        $page = "Leads";
        $this->lead = resolve(Lead::class)->with('customerDetail');
        view()->share('page', $page);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $leads = $this->lead->latest()->paginate(10);
        return view('admin.lead.index', compact('leads'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::get();
        $countryList = Country::get();
        $users = User::where('role_id', '2')->get();
        return view('admin.lead.create', compact('customers', 'countryList', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateLeadRequest $request)
    {
        $lastId =  Lead::all()->last() ? Lead::all()->last()->id + 1 : 1;
        $leadID = 'MP-LEAD-' . substr("00000{$lastId}", -6);
        $lead = new Lead;
        $lead->lead_id = $leadID;
        $lead->insurance_type = $request->insurance_type;
        $lead->invest_type = $request->invest_type;
        $lead->customer_id = $request->customer_id;
        $lead->lead_amount = $request->lead_amount;
        if ($request->invest_type == "investments") {
            if ($request->insurance_type == "pms") {
                $lead->product_name = $request->product_name;
                $lead->amount_of_investment = $request->amount_of_investment;
                $lead->investment_date = $request->investment_date;
                $lead->managed_by = $request->managed_by;
                $lead->lead_date = $request->lead_date;
            } elseif ($request->insurance_type == "mf") {
                $lead->product_name = $request->product_name;
                $lead->sip = $request->sip;
                $lead->lumsum_amount = $request->lumsum_amount;
                $lead->sip_amount = $request->sip_amount;
                $lead->amount_of_investment = $request->amount_of_investment;
                $lead->sip_date = $request->sip_date;
                $lead->investment_date = $request->investment_date;
                $lead->installment_no = $request->installment_no;
                $lead->managed_by = $request->managed_by;
                $lead->lead_date = $request->lead_date;
            } elseif ($request->insurance_type == "fd") {
                $lead->product_name = $request->product_name;
                $lead->amount_of_investment = $request->amount_of_investment;
                $lead->investment_date = $request->investment_date;
                $lead->interest_rate = $request->interest_rate;
                $lead->maturity_date = $request->maturity_date;
                $lead->managed_by = $request->managed_by;
                $lead->lead_date = $request->lead_date;
            } elseif ($request->insurance_type == "bond") {
                $lead->product_name = $request->product_name;
                $lead->amount_of_investment = $request->amount_of_investment;
                $lead->investment_date = $request->investment_date;
                $lead->interest_rate = $request->interest_rate;
                $lead->maturity_date = $request->maturity_date;
                $lead->managed_by = $request->managed_by;
                $lead->lead_date = $request->lead_date;
            }
        } elseif ($request->invest_type == "general insurance") {
            if ($request->insurance_type == "health") {
                $lead->insurer = $request->insurer;
                $lead->insured = $request->insured;
                $lead->product = $request->product;
                $lead->sub_product = $request->sub_product;
                $lead->received_date = $request->received_date;
                $lead->sum_insurance = $request->sum_insurance;
                $lead->insurer_dob = $request->insurer_dob;
                $lead->assignee = $request->assignee;
            } elseif ($request->insurance_type == "motor") {
                $lead->vehicle = $request->vehicle;
                $lead->client = $request->client;
                $lead->received_date = $request->received_date;
                $lead->vehicle_make = $request->vehicle_make;
                $lead->vehicle_model = $request->vehicle_model;
                $lead->assignee = $request->assignee;
                if ($request->hasFile('rc_copy')) {

                    $file = $request->file('rc_copy');

                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $filePath = $file->storeAs('insurance', $fileName, 'public');

                    $lead->rc_copy = $filePath;
                }
            } elseif ($request->insurance_type == "sme") {
                $lead->fire_burglary = $request->fire_burglary;
                $lead->marine = $request->marine;
                $lead->wc = $request->wc;
                $lead->gmc = $request->gmc;
                $lead->gpa = $request->gpa;
                $lead->other_insurance = $request->other_insurance;
            }
        } elseif ($request->invest_type == "travel") {
            $lead->client_name = $request->client_name;
            $lead->travel_from_date = $request->travel_from_date;
            $lead->travel_to_date = $request->travel_to_date;
            $lead->number_of_days = $request->number_of_days;
            $lead->travel_destination = $request->travel_destination;
            $lead->flight_preference = $request->flight_preference;
            $lead->other_services = $request->other_services;
            $lead->itinerary_flow = $request->itinerary_flow;
        }
        $insert = $lead->save();
        if ($insert) {
            foreach ($request->assigned_to as $key => $assign) {
                $leadMember = new LeadMember();
                $leadMember->user_id = $assign;
                $leadMember->lead_id = $lead->id;
                $leadMember->save();
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
        $lead = $this->lead->find($id);
        return view('admin.lead.show', compact('lead'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $lead = $this->lead->find($id);
        $customers = Customer::get();
        $countryList = Country::get();
        $users = User::where('role_id', '2')->get();
        return view('admin.lead.edit', compact('lead','customers','countryList','users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $lead = $this->lead->find($id);
        $lead->insurance_type = $request->insurance_type;
        $lead->invest_type = $request->invest_type;
        $lead->customer_id = $request->customer_id;
        $lead->lead_amount = $request->lead_amount;
        if ($request->invest_type == "investments") {
            if ($request->insurance_type == "pms") {
                $lead->product_name = $request->product_name;
                $lead->amount_of_investment = $request->amount_of_investment;
                $lead->investment_date = $request->investment_date;
                $lead->managed_by = $request->managed_by;
                $lead->lead_date = $request->lead_date;
            } elseif ($request->insurance_type == "mf") {
                $lead->product_name = $request->product_name;
                $lead->sip = $request->sip;
                $lead->lumsum_amount = $request->lumsum_amount;
                $lead->sip_amount = $request->sip_amount;
                $lead->amount_of_investment = $request->amount_of_investment;
                $lead->sip_date = $request->sip_date;
                $lead->investment_date = $request->investment_date;
                $lead->installment_no = $request->installment_no;
                $lead->managed_by = $request->managed_by;
                $lead->lead_date = $request->lead_date;
            } elseif ($request->insurance_type == "fd") {
                $lead->product_name = $request->product_name;
                $lead->amount_of_investment = $request->amount_of_investment;
                $lead->investment_date = $request->investment_date;
                $lead->interest_rate = $request->interest_rate;
                $lead->maturity_date = $request->maturity_date;
                $lead->managed_by = $request->managed_by;
                $lead->lead_date = $request->lead_date;
            } elseif ($request->insurance_type == "bond") {
                $lead->product_name = $request->product_name;
                $lead->amount_of_investment = $request->amount_of_investment;
                $lead->investment_date = $request->investment_date;
                $lead->interest_rate = $request->interest_rate;
                $lead->maturity_date = $request->maturity_date;
                $lead->managed_by = $request->managed_by;
                $lead->lead_date = $request->lead_date;
            }
        } elseif ($request->invest_type == "general insurance") {
            if ($request->insurance_type == "health") {
                $lead->insurer = $request->insurer;
                $lead->insured = $request->insured;
                $lead->product = $request->product;
                $lead->sub_product = $request->sub_product;
                $lead->received_date = $request->received_date;
                $lead->sum_insurance = $request->sum_insurance;
                $lead->insurer_dob = $request->insurer_dob;
                $lead->assignee = $request->assignee;
            } elseif ($request->insurance_type == "motor") {
                $lead->vehicle = $request->vehicle;
                $lead->client = $request->client;
                $lead->received_date = $request->received_date;
                $lead->vehicle_make = $request->vehicle_make;
                $lead->vehicle_model = $request->vehicle_model;
                $lead->assignee = $request->assignee;
                if ($request->hasFile('rc_copy')) {

                    $file = $request->file('rc_copy');

                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $filePath = $file->storeAs('insurance', $fileName, 'public');

                    $lead->rc_copy = $filePath;
                }
            } elseif ($request->insurance_type == "sme") {
                $lead->fire_burglary = $request->fire_burglary;
                $lead->marine = $request->marine;
                $lead->wc = $request->wc;
                $lead->gmc = $request->gmc;
                $lead->gpa = $request->gpa;
                $lead->other_insurance = $request->other_insurance;
            }
        } elseif ($request->invest_type == "travel") {
            $lead->client_name = $request->client_name;
            $lead->travel_from_date = $request->travel_from_date;
            $lead->travel_to_date = $request->travel_to_date;
            $lead->number_of_days = $request->number_of_days;
            $lead->travel_destination = $request->travel_destination;
            $lead->flight_preference = $request->flight_preference;
            $lead->other_services = $request->other_services;
            $lead->itinerary_flow = $request->itinerary_flow;
            $lead->assigned_to = $request->assigned_to;
        }
        $update = $lead->save();
        if ($update) {
            if($request->assigned_to !== null){
                foreach ($request->assigned_to as $key => $assign) {
                    $leadMember = new LeadMember();
                    $leadMember->user_id = $assign;
                    $leadMember->lead_id = $id;
                    $leadMember->save();
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
        $lastId =  Customer::all()->last() ? Customer::all()->last()->id + 1 : 1;
        $customerId = 'MP-CUS-' . substr("00000{$lastId}", -6);
        $data = [
            'name' => $request->name,
            'customer_id' => $customerId,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'original_password' => $request->password,
            'mobile_number' => $request->mobile_number,
            'gender' => $request->gender,
            'birth_date' => $request->birth_date,
            'pan_card_number' => $request->pan_card_number,
            'aadhaar_number' => $request->aadhaar_number,
            'service_preference' => $request->service_preference,
            'reference' => $request->reference,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country,
            'pin_code' => $request->pin_code,
            'role_id' => 3,
            'created_by' => Auth()->user()->id,
        ];

        $insert = Customer::create($data);
        if ($insert) {
            Log::create([
                'user_id' => auth()->user()->id,
                'module' => 'Customer',
                'description' => auth()->user()->name . " created a Customer named '" . $request->name . "'"
            ]);
            return response()->json(['status' => 1, 'data' => $insert, 'message' => 'Customer has been created successfully.'], 200);
        }
        return response()->json(['status' => 1, 'message' => 'Something went to wrong.'], 500);
    }
}
