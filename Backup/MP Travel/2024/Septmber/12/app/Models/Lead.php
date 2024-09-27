<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use HasFactory ,SoftDeletes;

    protected $guarded = ['id'];

    public function customerDetail(){
        return $this->hasOne(Customer::class,'id','customer_id');
    }

    public function leadMemberDetail(){
        return $this->hasMany(LeadMember::class,'lead_id','id');
    }

    public function userDetail(){
        return $this->belongsTo(User::class,'created_by');
    }
    public function leadTravelDetail(){
        return $this->hasMany(LeadTravelDetail::class,'lead_id','id');
    }
    public function leadAttachment(){
        return $this->hasMany(LeadAttachment::class,'lead_id','id');
    }

    public function followUpDetail(){
        return $this->hasMany(FollowUpEvent::class,'lead_id','id')->latest();
    }
    public function investmentLeadData(){
        return $this->hasOne(InvestmentLead::class,'lead_id','id');
    }
    public function travelLeadData(){
        return $this->hasOne(TravelLead::class,'lead_id','id');
    }
    public function insuranceLeadData(){
        return $this->hasOne(InsuranceLead::class,'lead_id','id');
    }
    public function existingCopyFiles(){
        return $this->hasMany(ExistingPolicyCopyLeads::class,'lead_id','id');
    }
    public function photographData(){
        return $this->hasMany(LeadPhotograph::class,'lead_id','id');
    }
    public function InvestigationReportData(){
        return $this->hasMany(LeadInvestigationReport::class,'lead_id','id');
    }
    public function employeeDataSheet(){
        return $this->hasMany(LeadEmployeeDataSheet::class,'lead_id','id');
    }
    public function leadDischargeSummaryData(){
        return $this->hasMany(LeadDischargeSummary::class,'lead_id','id');
    }
    public function insuranceFamilyData(){
        return $this->hasMany(LeadInsuranceFamilyMember::class,'lead_id','id');
    }
    public function leadReturnAttachment(){
        return $this->hasMany(LeadReturnAttachment::class,'lead_id','id');
    }
    public function claimHistoryData(){
        return $this->hasMany(ClaimHistoryAttachment::class,'lead_id','id');
    }
    public function claimDumpAttachment(){
        return $this->hasMany(ClaimDumpPolicyAttachment::class,'lead_id','id');
    }
    public function invoiceCopyAttachment(){
        return $this->hasMany(InvoiceCopyAttachment::class,'lead_id','id');
    }
    public function surveyReportAttachment(){
        return $this->hasMany(InvoiceCopyAttachment::class,'lead_id','id');
    }
}
