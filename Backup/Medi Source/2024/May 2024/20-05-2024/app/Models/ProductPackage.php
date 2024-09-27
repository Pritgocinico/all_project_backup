<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPackage extends Model
{
    use HasFactory;

    protected $fillable = ['varient_name', 'vial_price', 'vial_quantity', 'vial_total'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
