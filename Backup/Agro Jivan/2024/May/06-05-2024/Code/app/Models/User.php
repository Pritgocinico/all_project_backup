<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Helpers\UtilityHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $guarded = ['id'];

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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function roleDetail(){
        return $this->hasOne(Role::class,'id','role_id');
    }

    public function roles()
    {
        return $this
            ->belongsToMany('App\Models\Role')
            ->withTimestamps();
    }
    public function hasRole($role)
    {
      if ($this->roles()->where('name', $role)->first()) {
        return true;
      }
      return false;
    }

    public function departmentDetail(){
        return $this->hasMany(UserDepartment::class,'user_id','id');
    }
    public function permissionDetail(){
        return $this->hasMany(UserPermission::class,'user_id','id');
    }
    public function absentAttendanceDetail(){
        return $this->hasMany(Attendance::class,'user_id','id');
    }
    public function presentAttendanceDetail(){
        return $this->hasMany(Attendance::class,'user_id','id');
    }
    public function teamDetail(){
        return $this->hasOne(TeamDetail::class,'user_id','id');
    }
    public function confirmOrder(){
        return $this->hasMany(Order::class,'created_by','id')->where('order_status',6);
    }

    public function onDeliverBatch(){
        return $this->hasMany(Batch::class,'driver_id','id')->where('status',1);
    }

    public function deliveredBatch(){
        return $this->hasMany(Batch::class,'driver_id','id')->where('status',2);
    }

    public function returnOrder(){
        return $this->hasMany(Order::class,'created_by','id')->where('order_status',5);
    }
    public function allOrder(){
        return $this->hasMany(Order::class,'created_by','id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'created_by');
    }

    public function attendancesDetail()
    {
        return $this->hasMany(Attendance::class);
    }
}
