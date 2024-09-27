<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'logo',
        'favicon',
        'company_name',
        'site_url',
        'date_format',
        'created_at',
        'updated_at',
        'address',
        'city',
        'state',
        'postal_code',
        'phone',
        'gst_number',
        'email',
        'password',
    ];
}
