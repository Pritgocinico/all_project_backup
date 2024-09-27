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
use App\Models\Department;
use App\Models\ExistingPolicyCopyLeads;
use App\Models\FollowUpEvent;
use App\Models\FollowUpMember;
use App\Models\InsuranceLead;
use App\Models\InvestmentLead;
use App\Models\LeadAttachment;
use App\Models\LeadDischargeSummary;
use App\Models\LeadEmployeeDataSheet;
use App\Models\LeadInsuranceFamilyMember;
use App\Models\LeadInvestigationReport;
use App\Models\LeadPhotograph;
use App\Models\LeadReturnAttachment;
use App\Models\LeadTravelDetail;
use App\Models\PaPolicyAttachment;
use App\Models\Setting;
use App\Models\TravelLead;
use Carbon\Carbon;
use PDF;
use Illuminate\Support\Str;
use Pusher\Pusher;
use Notification;
use App\Notifications\SendNotification;
use PDO;

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
        $type = request('type');
        $leads = $this->lead->when($type == "assign", function ($query) {
            $query->WhereHas('leadMemberDetail', function ($query) {
                $query->where('user_id', Auth()->user()->id);
            });
        })->when($type == "create", function ($query) {
            $query->where('created_by', Auth()->user()->id);
        })
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
                        ->orWhere('lead_amount', 'like', '%' . $search . '%')
                        ->orWhereHas('customerDetail', function ($query) use ($search) {
                            $query->where('customers.name', 'like', '%' . $search . '%');
                        })->orWhereHas('userDetail', function ($query) use ($search) {
                            $query->where('name', 'like', '%' . $search . '%');
                        });
                });
            })
            ->latest()
            ->get();
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
        $customerId = 'MP-CUS-' . substr("00000{$lastId}", -6);
        $customers = Customer::get();
        $departmentList = Department::get();
        $countryList = Country::get();
        $users = User::where('role_id', '2')->get();
        return view('admin.lead.create', compact('customers', 'countryList', 'users', 'customerId', 'departmentList'));
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
        $lead->customer_id = $request->customer_id;
        $lead->department = $request->department;
        $lead->lead_amount = $request->lead_amount;
        $lead->description = $request->description;
        $lead->lead_date = $request->lead_date;
        $lead->created_by = Auth()->user()->id;
        $insert = $lead->save();
        if ($insert) {
            if ($request->invest_type == "investments") {
                $investmentLead = new InvestmentLead();
                $investmentLead->lead_id = $lead->id;
                $investmentLead->investment_type = $request->invest_insurance_type;
                $lead->invest_type = $request->invest_type;
                $lead->investment_code = $request->investment_code;
                $lead->investment_remark = $request->investment_remark;
                $investmentLead->kyc_status = $request->kyc_status;
                $investmentLead->investment_date = $request->investment_date;
                $investmentLead->invest_amount = $request->amount_of_investment;
                $investmentLead->sip_date = $request->sip_date;
                $investmentLead->tenure = $request->tenure;
                $investmentLead->investment_payout = $request->interest_payout;
                $investmentLead->lumsum_amount = $request->lumsum_amount;
                $investmentLead->rate_of_interest = $request->interest_rate;
                $investmentLead->maturity_date = $request->maturity_date;
                $investmentLead->investment_mode = $request->interest_mode;
                $investmentLead->sip_amount = $request->sip_amount;
                if ($request->hasFile('cancel_cheque')) {
                    $fileCancel = $request->file('cancel_cheque');
                    $fileCancelName = time() . '_' . $fileCancel->getClientOriginalName();
                    $fileCancelPath = $fileCancel->storeAs('insurance', $fileCancelName, 'public');
                    $investmentLead->cancel_cheque = $fileCancelPath;
                }
                $investmentLead->save();
            } elseif ($request->invest_type == "general insurance") {
                $insuranceLead = new InsuranceLead();
                $insuranceLead->lead_id = $lead->id;
                $insuranceLead->insurance_type = $request->insurance_type;
                $insuranceLead->risk_location_address = $request->risk_location_address;
                $insuranceLead->risk_location_country = $request->risk_location_country;
                $insuranceLead->risk_location_state = $request->risk_location_state;
                $insuranceLead->risk_location_pin_code = $request->risk_location_pin_code;
                $insuranceLead->risk_location_city = $request->insurance_code;
                $insuranceLead->policy_type = $request->health_policy_type;
                if($request->health_policy_type == "renewal"){
                    $insuranceLead->previous_policy = $request->previous_policy;
                    $insuranceLead->sum_insurance = $request->sum_insurance;
                    $insuranceLead->expiry_date = $request->expiry_date;
                }
                if (isset($request->same_address)) {
                    $customerData = Customer::find($request->customer_id);
                    $insuranceLead->risk_location_address = $customerData->address;
                    $insuranceLead->risk_location_country = $customerData->country;
                    $insuranceLead->risk_location_state = $customerData->state;
                    $insuranceLead->risk_location_pin_code = $customerData->pin_code;
                    $insuranceLead->risk_location_city = $customerData->city;
                }
                $insuranceLead->policy_start_date = $request->policy_period_start_date;
                $insuranceLead->policy_end_date = $request->policy_period_end_date;
                if ($request->insurance_type == "fire_policy") {
                    $insuranceLead->building_value = $request->building_value;
                    $insuranceLead->plant_machinery = $request->plant_machinary_value;
                    $insuranceLead->fff_other_ee = $request->fff_other_ee;
                    $insuranceLead->other_content = $request->other_content;
                    $insuranceLead->total_sum_insured = $request->sum_Insured;
                    $insuranceLead->policy_type = $request->type_of_policy;
                    $insuranceLead->operational_fire = $request->operational_fire;
                    $insuranceLead->maintain_electric = $request->maintain_electrical;
                    $insuranceLead->water_drainage = $request->provision_storm_water_drainage;
                    $insuranceLead->security_cctv = $request->security_and_cctv;
                    $insuranceLead->three_year_claim_history = $request->three_year_claim_history;
                    $insuranceLead->basement = $request->basement;
                    $insuranceLead->use_basement = $request->use_basement_other_operation;
                    $insuranceLead->insured_premises = $request->insure_premises;
                    $insuranceLead->risk_locate = $request->rick_located_area_access;
                    $insuranceLead->age_of_building = $request->age_building;
                } else if ($request->insurance_type == "wc_policy") {
                    $insuranceLead->nature_business = $request->nature_of_business;
                    $insuranceLead->risk_occupancy = $request->risk_occupancy_scope_work;
                    $insuranceLead->sub_contractor = $request->sub_contract_coverage;
                    $insuranceLead->occupational_disease = $request->occupatioanal_diseases_covarage;
                    $insuranceLead->total_employees = $request->total_employee;
                    $insuranceLead->total_wages = $request->total_wage;
                    $insuranceLead->skilled = $request->skilled_employee;
                    $insuranceLead->un_skilled = $request->unskilled_employee;
                    $insuranceLead->commercial_travel = $request->commercial_travel;
                    $insuranceLead->three_year_claim_history = $request->claim_history;
                    $insuranceLead->medical_extension = $request->medical_extension_limit;
                    $insuranceLead->number_shift = $request->number_shift;
                    $insuranceLead->security_cctv = $request->cctv_camera_install;
                    $insuranceLead->number_shift = $request->actual_amount;
                    $insuranceLead->distance_near_hospital = $request->distance_near_hospital;
                    $insuranceLead->first_aid_kit = $request->first_aid_kit;
                    $insuranceLead->fire_extinguishers = $request->fire_extinguishers;
                    $insuranceLead->wc_security_person = $request->wc_security_person;
                } else if($request->insurance_type == "health_policy"){
                    $insuranceLead->policy_number = $request->health_policy_number;
                    $insuranceLead->expiry_date = $request->health_policy_expiry_date;
                    $insuranceLead->total_sum_insured = $request->health_sum_insured;
                    $insuranceLead->health_claim_history = $request->health_claim_history;
                    $insuranceLead->health_claim_detail = $request->health_claim_history_detail;
                    $insuranceLead->alcohol_consumer = $request->alcohol_consumer;
                    $insuranceLead->tobacco_consumer = $request->tobacco_consumer;
                    $insuranceLead->smoking = $request->smoking;
                    $insuranceLead->ped_medical = $request->ped_medicines_details;
                    $insuranceLead->CIR = $request->cir;
                    $insuranceLead->policy_start_date = $request->health_policy_start_date;
                    $insuranceLead->policy_end_date = $request->health_policy_start_date;
                } else if($request->insurance_type == "pa_policy"){
                    $insuranceLead->pa_company_name = $request->pa_company_name;
                    $insuranceLead->occupation = $request->occupation;
                    $insuranceLead->type_case = $request->pa_type_case;
                    $insuranceLead->policy_insurer_name = $request->policy_insurer_name;
                    $insuranceLead->total_sum_insured = $request->pa_sum_insured;
                    $insuranceLead->physical_disable = $request->physical_disable;
                    $insuranceLead->monthly_salary = $request->gross_monthly_salary;
                    $insuranceLead->accident_coverage = $request->accidental_death;
                    $insuranceLead->permanent_disability = $request->permanent_disability;
                    $insuranceLead->loss_income_benefit = $request->loss_of_income;
                    $insuranceLead->fracture_care = $request->fracture_care;
                    $insuranceLead->travel_expense_benefit = $request->hospital_cash_benefit;
                    $insuranceLead->travel_expense_benefit = $request->travel_expenses_benefit;
                    $insuranceLead->accidental_hospitalization_expenses = $request->accidental_hospitalization_expenses;
                    $insuranceLead->adventure_sports_benefit = $request->adventure_sports_benefit;
                    $insuranceLead->air_ambulance_cover = $request->air_ambulance_cover;
                    $insuranceLead->child_education_benefit = $request->childrens_education_benefit;
                    $insuranceLead->comma_due_accident = $request->coma_due_to_accidental_bodily_care;
                    $insuranceLead->emi_payment_cover = $request->emi_payment_cover;
                    $insuranceLead->policy_start_date = $request->pa_policy_start_date;
                    $insuranceLead->policy_end_date = $request->pa_policy_start_date;
                }
                $insuranceLead->save();
            } elseif ($request->invest_type == "travel") {
                $travelLead  = new TravelLead();
                $travelLead->lead_id = $lead->id;
                $travelLead->travel_from = $request->travel_from_date;
                $travelLead->travel_to = $request->travel_to_date;
                $travelLead->no_of_pax = $request->number_of_prex;
                $travelLead->travel_destination = $request->travel_destination;
                $travelLead->flight_preference = $request->flight_preference;
                $travelLead->hotel_preference = $request->hotel_preference;
                $travelLead->remarks = $request->itinerary_flow;
                $travelLead->other_service = implode(', ', $request->other_services);
                $travelLead->no_of_day = UtilityHelper::getDiffInDays($request->travel_from_date, $request->travel_to_date);
                $travelLead->other_service_charge = $request->other_service_charge;
                $travelLead->save();
            }

            if ($request->hasFile('other_attachment')) {
                foreach ($request->file('other_attachment') as $key => $policyAtt) {
                    $policyAttachment = new LeadAttachment();
                    $policyAttachment->lead_id = $lead->id;
                    $newFilename = Str::random(40) . '.' . $policyAtt->getClientOriginalExtension();
                    $pathLogo = $policyAtt->storeAs('insurance/attachment', $newFilename, 'public');
                    $policyAttachment->attachments = $pathLogo;
                    $policyAttachment->save();
                }
            }
            if ($request->hasFile('wc_other_attachmet')) {
                foreach ($request->file('wc_other_attachmet') as $key => $wcOtherAttach) {
                    $wcOther = new LeadAttachment();
                    $wcOther->lead_id = $lead->id;
                    $newFilename = Str::random(40) . '.' . $wcOtherAttach->getClientOriginalExtension();
                    $pathLogo = $wcOtherAttach->storeAs('insurance/attachment', $newFilename, 'public');
                    $wcOther->attachments = $pathLogo;
                    $wcOther->save();
                }
            }
            if ($request->hasFile('pa_other_attachment')) {
                foreach ($request->file('pa_other_attachment') as $key => $paOtherAttachment) {
                    $paOther = new LeadAttachment();
                    $paOther->lead_id = $lead->id;
                    $newFilename = Str::random(40) . '.' . $paOtherAttachment->getClientOriginalExtension();
                    $pathLogo = $paOtherAttachment->storeAs('insurance/attachment', $newFilename, 'public');
                    $paOther->attachments = $pathLogo;
                    $paOther->save();
                }
            }
            if ($request->hasFile('pa_policy_attachement')) {
                foreach ($request->file('pa_policy_attachement') as $key => $paAttachment) {
                    $returnAttachment = new LeadReturnAttachment();
                    $returnAttachment->lead_id = $lead->id;
                    $returnAttachment->insurance_type = $request->insurance_type;
                    $newFilename = Str::random(40) . '.' . $paAttachment->getClientOriginalExtension();
                    $pathLogo1 = $paAttachment->storeAs('insurance/attachment', $newFilename, 'public');
                    $returnAttachment->file_path = $pathLogo1;
                    $returnAttachment->ext = $paAttachment->getClientOriginalExtension();;
                    $returnAttachment->file_name = $paAttachment->getClientOriginalName();;
                    $returnAttachment->save();
                }
            }
            if ($request->hasFile('existing_policy_copy')) {
                foreach ($request->file('existing_policy_copy') as $key => $existCopy) {
                    $existPolicyCopy = new ExistingPolicyCopyLeads();
                    $existPolicyCopy->lead_id = $lead->id;
                    $existPolicyCopy->insurance_type = $request->insurance_type;
                    $newFilename = Str::random(40) . '.' . $existCopy->getClientOriginalExtension();
                    $pathLogo2 = $existCopy->storeAs('insurance/attachment', $newFilename, 'public');
                    $existPolicyCopy->file_path = $pathLogo2;
                    $existPolicyCopy->ext = $existCopy->getClientOriginalExtension();;
                    $existPolicyCopy->file_name = $existCopy->getClientOriginalName();;
                    $existPolicyCopy->save();
                }
            }
            if ($request->hasFile('wc_exist_policy')) {
                foreach ($request->file('wc_exist_policy') as $key => $existCopy) {
                    $existPolicyCopy = new ExistingPolicyCopyLeads();
                    $existPolicyCopy->lead_id = $lead->id;
                    $existPolicyCopy->insurance_type = $request->insurance_type;
                    $newFilename = Str::random(40) . '.' . $existCopy->getClientOriginalExtension();
                    $pathLogo2 = $existCopy->storeAs('insurance/attachment', $newFilename, 'public');
                    $existPolicyCopy->file_path = $pathLogo2;
                    $existPolicyCopy->ext = $existCopy->getClientOriginalExtension();;
                    $existPolicyCopy->file_name = $existCopy->getClientOriginalName();;
                    $existPolicyCopy->save();
                }
            }
            if ($request->hasFile('health_existing_policy_copies')) {
                foreach ($request->file('health_existing_policy_copies') as $key => $healthExist) {
                    $healthExistCopy = new ExistingPolicyCopyLeads();
                    $healthExistCopy->lead_id = $lead->id;
                    $healthExistCopy->insurance_type = $request->insurance_type;
                    $newFilename = Str::random(40) . '.' . $healthExist->getClientOriginalExtension();
                    $pathLogo2 = $healthExist->storeAs('insurance/attachment', $newFilename, 'public');
                    $healthExistCopy->file_path = $pathLogo2;
                    $healthExistCopy->ext = $healthExist->getClientOriginalExtension();;
                    $healthExistCopy->file_name = $healthExist->getClientOriginalName();;
                    $healthExistCopy->save();
                }
            }
            if ($request->hasFile('health_discharge_claim')) {
                foreach ($request->file('health_discharge_claim') as $key => $healthDischarge) {
                    $healthDischargeClaim = new LeadDischargeSummary();
                    $healthDischargeClaim->lead_id = $lead->id;
                    $healthDischargeClaim->insurance_type = $request->insurance_type;
                    $newFilename = Str::random(40) . '.' . $healthDischarge->getClientOriginalExtension();
                    $pathLogo2 = $healthDischarge->storeAs('insurance/attachment', $newFilename, 'public');
                    $healthDischargeClaim->file_path = $pathLogo2;
                    $healthDischargeClaim->ext = $healthDischarge->getClientOriginalExtension();;
                    $healthDischargeClaim->file_name = $healthDischarge->getClientOriginalName();;
                    $healthDischargeClaim->save();
                }
            }
            if ($request->hasFile('pa_policy_attachement')) {
                foreach ($request->file('pa_policy_attachement') as $key => $paPolicyAttachment) {
                    $paPolicy = new PaPolicyAttachment();
                    $paPolicy->lead_id = $lead->id;
                    $paPolicy->insurance_type = $request->insurance_type;
                    $newFilename = Str::random(40) . '.' . $paPolicyAttachment->getClientOriginalExtension();
                    $pathLogo2 = $paPolicyAttachment->storeAs('insurance/attachment', $newFilename, 'public');
                    $paPolicy->file_path = $pathLogo2;
                    $paPolicy->ext = $paPolicyAttachment->getClientOriginalExtension();;
                    $paPolicy->file_name = $paPolicyAttachment->getClientOriginalName();;
                    $paPolicy->save();
                }
            }
            if ($request->hasFile('photopgaphs')) {
                foreach ($request->file('photopgaphs') as $key => $photograph) {
                    $leadPhoto = new LeadPhotograph();
                    $leadPhoto->lead_id = $lead->id;
                    $leadPhoto->insurance_type = $request->insurance_type;
                    $newFilename = Str::random(40) . '.' . $photograph->getClientOriginalExtension();
                    $pathLogo2 = $photograph->storeAs('insurance/attachment', $newFilename, 'public');
                    $leadPhoto->file_path = $pathLogo2;
                    $leadPhoto->ext = $photograph->getClientOriginalExtension();;
                    $leadPhoto->file_name = $photograph->getClientOriginalName();;
                    $leadPhoto->save();
                }
            }
            if ($request->hasFile('invetigation_report')) {
                foreach ($request->file('invetigation_report') as $key => $investigation) {
                    $investReport = new LeadInvestigationReport();
                    $investReport->lead_id = $lead->id;
                    $investReport->insurance_type = $request->insurance_type;
                    $newFilename = Str::random(40) . '.' . $investigation->getClientOriginalExtension();
                    $pathLogo2 = $investigation->storeAs('insurance/attachment', $newFilename, 'public');
                    $investReport->file_path = $pathLogo2;
                    $investReport->ext = $investigation->getClientOriginalExtension();;
                    $investReport->file_name = $investigation->getClientOriginalName();;
                    $investReport->save();
                }
            }
            if ($request->hasFile('employee_data_sheet')) {
                foreach ($request->file('employee_data_sheet') as $key => $dataSheet) {
                    $empDataSheet = new LeadEmployeeDataSheet();
                    $empDataSheet->lead_id = $lead->id;
                    $empDataSheet->insurance_type = $request->insurance_type;
                    $newFilename = Str::random(40) . '.' . $dataSheet->getClientOriginalExtension();
                    $pathLogo2 = $dataSheet->storeAs('insurance/attachment', $newFilename, 'public');
                    $empDataSheet->file_path = $pathLogo2;
                    $empDataSheet->ext = $dataSheet->getClientOriginalExtension();;
                    $empDataSheet->file_name = $dataSheet->getClientOriginalName();;
                    $empDataSheet->save();
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
            foreach ($request->member_name as $key => $memName) {
                $healthMemberData = new LeadInsuranceFamilyMember();
                $healthMemberData->lead_id = $lead->id;
                $healthMemberData->name = $memName;
                $healthMemberData->dob = $request->dob[$key];
                $healthMemberData->gender = $request->gender[$key];
                $healthMemberData->relation = $request->relationship[$key];
                $healthMemberData->pre_existing = $request->pre_existing[$key];
                $healthMemberData->height = $request->height[$key];
                $healthMemberData->weight = $request->weight[$key];
                $healthMemberData->education = $request->education[$key];
                $healthMemberData->profession = $request->profession[$key];
                $healthMemberData->save();
            }
            $followUp = new FollowUpEvent();
            $followUp->lead_id = $lead->id;
            $followUp->event_name = "need to follow up";
            $followUp->event_start = Carbon::now()->format('Y-m-d');
            $followUp->event_start = Carbon::tomorrow()->format('Y-m-d');
            $followUp->created_by = Auth()->user()->id;
            $followUp->event_status = 1;
            $followUp->save();
            foreach ($request->assigned_to as $key => $member) {
                $followMember = new FollowUpMember();
                $followMember->user_id = $member;
                $followMember->followup_id = $followUp->id;
                $followMember->save();
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
        $lastId =  Customer::all()->last() ? Customer::all()->last()->id + 1 : 1;
        $customerId = 'MP-CUS-' . substr("00000{$lastId}", -6);
        $lead = $this->lead->find($id);
        $leadMemberDetail = LeadMember::where('lead_id', $id)->pluck('user_id')->toArray();
        $customers = Customer::get();
        $countryList = Country::get();
        $users = User::where('role_id', '2')->get();
        $departmentList = Department::get();
        return view('admin.lead.edit', compact('customerId', 'departmentList', 'lead', 'customers', 'countryList', 'users', 'leadMemberDetail'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        dd($request->all());
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
                $lead->tenure = $request->tenure;
                $lead->interest_payout = $request->interest_payout;
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
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'mobile_number' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'message' =>  $validator->errors()->first()], 404);
        }
        $lastId =  Customer::all()->last() ? Customer::all()->last()->id + 1 : 1;
        $customerId = 'MP-CUS-' . substr("00000{$lastId}", -6);
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
}
