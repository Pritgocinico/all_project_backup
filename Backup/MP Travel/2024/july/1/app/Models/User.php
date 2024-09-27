<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarder = ['id'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function paginate($count = 10) {
        return $this->latest()->paginate($count);
    }

    public function stateDetail(){
        return $this->hasOne(State::class,'id','state');
    }
    public function countryDetail(){
        return $this->hasOne(Country::class,'iso2','country');
    }
    public function cityDetail(){
        return $this->hasOne(City::class,'id','city');
    }
    public function roleDetail(){
        return $this->hasOne(Role::class,'id','role_id');
    }
}
