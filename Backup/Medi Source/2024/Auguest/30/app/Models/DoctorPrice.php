<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorPrice extends Model
{
    use HasFactory;
    protected $fillable = [
        'doctor_id', 
        'product_id',
        'package_id',
        'price',

    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
}
