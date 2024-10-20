<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InfoSheet extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded = ['id'];
    protected $table = 'infosheets';

    public function paginate($count = 10) {
        return $this->latest()->paginate($count);
    }
}
