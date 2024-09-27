<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'name',
        'phone',
        'email',
        'role',
        'last_login_at',
        'customer_id',
    ];
    public function customer()
    {
        return $this
            ->belongsToMany('App\Models\Customer')
            ->withTimestamps();
    }
    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    public function cityDetail(){
        return $this->hasOne(City::class,'id','city');
    }
    public function stateDetail(){
        return $this->hasOne(State::class,'id','state');
    }
}
