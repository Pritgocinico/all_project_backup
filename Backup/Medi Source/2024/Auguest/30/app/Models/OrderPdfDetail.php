<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderPdfDetail extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded = ['id'];

    public function orderProductPdfDetail()
    {
        return $this->hasMany(OrderProductPdfDetail::class,'order_pdf_detail_id','id');
    }
}