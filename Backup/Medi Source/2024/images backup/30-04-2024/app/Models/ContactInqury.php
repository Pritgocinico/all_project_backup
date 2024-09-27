<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactInqury extends Model
{
    use HasFactory;
    protected $table = 'contact_inquiries';

    protected $fillable = [
        'user_type',
        'clinical_difference',
        'first_name',
        'last_name',
        'email',
        'phone',
        'clinic_name',
        'website',
        'number_of_physicians',
        'number_of_locations',
        'license_number',
        'dea_number',
        'products_services_interested',
        'description',
    ];
}
