<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Company;
use App\Models\User;
use App\Models\SourcingAgent;
use App\Models\PolicyPayment;
use App\Models\PolicyParameter;
use App\Models\Customer;

class Policy extends Model
{
    use HasFactory, SoftDeletes;

    public function customers(){
        return $this->belongsTo('App\Models\Customer','customer','id')->withDefault();
    }
    public function category(){
        return $this->belongsTo('App\Models\Category','sub_category','id')->withDefault();
    }
    public function parentPolicy()
    {
        return $this->belongsTo(Policy::class, 'pyp_no', 'policy_no');
    }

    // Define a recursive relationship for retrieving child policies
    public function childPolicies()
    {
        return $this->hasMany(Policy::class, 'pyp_no', 'policy_no');
    }

    // Custom method to recursively fetch policies
    public static function getPoliciesRecursively($policyNo,$id, &$visited = [])
    {
       
        $policies = Policy::where('policy_no', $policyNo)->orderBy('created_at', 'desc')->get();
        $policy_first = Policy::where('id', $id)->first();
        // Check if any policies were found
        if ($policies->isNotEmpty()) {
            if (in_array($id, $visited)) {
                $recursivePolicies = collect([]);
            }else{
                $recursivePolicies = collect([$policy_first]);
            }
            $visited[] = $id;
            
            // $recursivePolicies->push($policy_first);
            foreach ($policies as $policy) {
                
                // Recursively fetch child policies
                $childPolicies = $policy->childPolicies;
                // If child policies exist, recursively fetch their child policies
                 if ($policy->id != $id) {
                    $recursivePolicies->push($policy);
                }
                if ($childPolicies->isNotEmpty()) {
                    $recursivePolicies = $recursivePolicies->merge(
                        self::getPoliciesRecursively($policy->pyp_no,$id,$visited)
                    );
                }
               
                // Merge current policy and its child policies
                // $recursivePolicies->push($policy);
            }
            return $recursivePolicies;
        }

        return collect([]);
    }
    
    public function company()
    {
        return $this->belongsTo(Company::class, 'company', 'id');
    }


    public function agent()
    {
        return $this->belongsTo(User::class, 'agent');
    }

    public function teamLead()
    {
        return $this->belongsTo(User::class, 'team_lead');
    }

    public function sourcingAgent()
    {
        return $this->belongsTo(SourcingAgent::class, 'agent');
    }

    public function payments()
    {
        return $this->hasMany(PolicyPayment::class);
    }

    public function parameters()
    {
        return $this->hasMany(PolicyParameter::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer');
    }
}
