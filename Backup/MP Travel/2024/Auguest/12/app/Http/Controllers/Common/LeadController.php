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
use App\Helpers\UtilityHelper;
use App\Models\LeadAttachment;
use App\Models\LeadTravelDetail;
use Dompdf\Dompdf;
use Illuminate\Support\Str;

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

    public function leadAjaxList(Request $request)
    {
        $search = $request->search;
        $leads = $this->lead
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
            ->paginate(10);
        return view('admin.lead.ajax_list', compact('leads'));
    }

    public function exportFile(Request $request)
    {
        $search = $request->search;

        $leads = $this->lead
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
            $mainLogoUrl = asset('storage/logo/UQcj8rCZqMmTVJ89a4zx0Vzz7UZ5AtbbWazxf2cd.png');
            $html = view('admin.pdf.leads', ['leads' => $leads, 'mainLogoUrl' => $mainLogoUrl])->render();

            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            return response()->streamDownload(
                function () use ($dompdf) {
                    echo $dompdf->output();
                },
                'Lead.pdf'
            );
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $lastId =  Customer::all()->last() ? Customer::all()->last()->id + 1 : 1;
        $customerId = 'MP-CUS-' . substr("00000{$lastId}", -6);
        $customers = Customer::get();
        $countryList = Country::get();
        $users = User::where('role_id', '2')->get();
        return view('admin.lead.create', compact('customers', 'countryList', 'users', 'customerId'));
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
        $lead->description = $request->description;
        $lead->created_by = Auth()->user()->id;
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
            if ($request->hasFile('gst_certificate')) {
                $gstFile = $request->file('gst_certificate');
                $newFilename = Str::random(40) . '.' . $gstFile->getClientOriginalExtension();
                $pathLogo = $gstFile->storeAs('insurance/gst', $newFilename, 'public');
                $lead->gst_certificate = $pathLogo;
            }
            if ($request->hasFile('old_policy_attachment')) {
                $attachments = [];
                foreach ($request->file('old_policy_attachment') as $fileOldPolicy) {
                    $filename = time() . '_' . $fileOldPolicy->getClientOriginalName();
                    $fileOldPolicy->storeAs('insurance/old_policy', $filename);
                    $attachments[] = $filename;
                }
                $lead->old_policy_copy_attachment = json_encode($attachments);
            }
            if ($request->hasFile('claim_history')) {
                $claimAttachments = [];
                foreach ($request->file('claim_history') as $claimAttachmentsFile) {
                    $filename = time() . '_' . $claimAttachmentsFile->getClientOriginalName();
                    $claimAttachmentsFile->storeAs('insurance/claim/', $filename);
                    $claimAttachments[] = $filename;
                }
                $lead->claim_history = json_encode($claimAttachments);
            }
            $lead->policy_type = $request->policy_type;
            $lead->previous_policy = $request->previous_policy;
            $lead->sum_insurance = $request->sum_insurance;
            $lead->health_policy_type = $request->health_policy_type;
        } elseif ($request->invest_type == "travel") {
            $lead->client_name = $request->client_name;
            $lead->travel_from_date = $request->travel_from_date;
            $lead->travel_to_date = $request->travel_to_date;
            $lead->number_of_prex = $request->number_of_prex;
            $lead->number_of_days = UtilityHelper::getDiffInDays($request->travel_from_date, $request->travel_to_date);
            $lead->travel_destination = $request->travel_destination;
            $lead->flight_preference = $request->flight_preference;
            $lead->other_services = $request->other_services;
            $lead->itinerary_flow = $request->itinerary_flow;
        }
        $insert = $lead->save();
        if ($insert) {
            if ($request->hasFile('policy_attachment')) {
                foreach ($request->file('policy_attachment') as $key => $policyAtt) {
                    $policyAttachment = new LeadAttachment();
                    $policyAttachment->lead_id = $lead->id;
                    $newFilename = Str::random(40) . '.' . $policyAtt->getClientOriginalExtension();
                    $pathLogo = $policyAtt->storeAs('insurance/attachment', $newFilename, 'public');
                    $policyAttachment->attachments = $pathLogo;
                    $policyAttachment->save();
                }
            }
            if ($request->child_type !== null) {
                foreach ($request->child_type as $key => $child) {
                    $leadTravelDetail = new LeadTravelDetail();
                    $leadTravelDetail->lead_id = $lead->id;
                    $leadTravelDetail->customer_id = $request->customer_id;
                    $leadTravelDetail->child_type = $child;
                    $leadTravelDetail->child_name = $request->child_name[$key];
                    $leadTravelDetail->child_age = $request->child_age[$key];
                    $leadTravelDetail->save();
                }
            }
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
        $leadMemberDetail = LeadMember::where('lead_id', $id)->pluck('user_id')->toArray();
        $customers = Customer::get();
        $countryList = Country::get();
        $users = User::where('role_id', '2')->get();
        return view('admin.lead.edit', compact('lead', 'customers', 'countryList', 'users', 'leadMemberDetail'));
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
            $lead->number_of_days = UtilityHelper::getDiffInDays($request->travel_from_date, $request->travel_to_date);
            $lead->number_of_prex = $request->number_of_prex;
            $lead->travel_destination = $request->travel_destination;
            $lead->flight_preference = $request->flight_preference;
            $lead->other_services = $request->other_services;
            $lead->itinerary_flow = $request->itinerary_flow;
            $lead->assigned_to = $request->assigned_to;
        }
        $update = $lead->save();
        if ($update) {
            if ($request->assigned_to !== null) {
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
        $customer = new Customer();
        $customer->insurance = $request->insurance;
        $customer->insurance_type = $request->insurance_type;
        $customer->customer_department = $request->customer_department;
        $customer->name = $request->name;
        $customer->customer_id = $request->customer_id;
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
        $insert = $customer->save();
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
