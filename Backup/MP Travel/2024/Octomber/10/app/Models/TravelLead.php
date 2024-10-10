<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravelLead extends Model
{
    use HasFactory;

    public function visaCountryName(){
        return $this->hasOne(Country::class, 'iso2', 'travel_country');
    }

    public function rejectVisaCountryName(){
        return $this->hasOne(Country::class, 'iso2', 'visa_rejection_country');
    }
    public function otherServiceName(){
        return $this->hasOne(ServicePreference::class, 'id', 'domestic_other_services');
    }
}
