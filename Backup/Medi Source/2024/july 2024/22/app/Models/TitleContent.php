<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TitleContent extends Model
{
    use HasFactory;
    
    protected $fillable = ['title', 'content'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
