<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory;
    
    use SoftDeletes;
    
    protected $fillable = [
        'project_generated_id',
        'customer_id',
        'phone_number',
        'email',
        'address',
        'cityname',
        'state',
        'zipcode',
        'description',
        'project_confirm_date',
        'start_date',
        'estimated_end_date',
        'measurement_date',
        'reference_name',
        'reference_phone',
        'architecture_name', 
        'architecture_number', 
        'supervisor_name', 
        'supervisor_number',
        'status',
        'step',
        'sub_project_id',
        'type',
        // ... other fields
    ];
    // protected $dates = [
    //     'project_confirmation_date',
    //     'start_date',
    //     'estimated_end_date',
    //     'estimated_measurement_date',
       
    // ];
    public function user()
    {
        return $this->belongsTo(User::class , 'customer_id');
    }
    public function quotationfiles()
    {
        return $this->hasMany(Quotationfile::class);
    }
    public function measurements()
    {
        return $this->hasMany(Measurement::class);
    }
    public function measurementfiles()
    {
        return $this->hasMany(Measurement::class);
    }
    public function quotations()
    {
        return $this->hasMany(Quotation::class);
    }
    public function workshops()
    {
        return $this->hasMany(Workshop::class);
    }
    public function fitting()
    {
        return $this->hasMany(Fitting::class);
    }
    public function material()
    {
        return $this->hasMany(Material::class);
    }
    public function purchaseFile()
    {
        return $this->hasMany(Purchase::class);
    }
    public function glassMeasurementFile()
    {
        return $this->hasMany(GlassMeasurementFile::class);
    }
    // Project.php (or your relevant model file)

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    public function additionalProjectWork(){
        return $this->hasOne(AdditionalProjectDetail::class,'project_id','id');
    }

}
