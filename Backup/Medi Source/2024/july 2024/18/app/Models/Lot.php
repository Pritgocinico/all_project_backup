<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lot extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'lot_number',
        'product_id',
        'description',
        'file',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
