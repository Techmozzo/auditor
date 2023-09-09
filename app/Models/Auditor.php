<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Auditor extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
         'email' ,'phone', 'password','role_id', 'email_verified_at', 'is_blocked', 'company_id', 'is_verified'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function name(){
        return $this->profile->first_name.' '. $this->profile->last_name;
    }

    public function initials(){
        return substr($this->profile->first_name, 0, 1) .' '. substr($this->profile->last_name, 0, 1);
    }

    public  function role(){
        return $this->belongsTo(Role::class);
    }

    public function hasRole($role){
        return null !== $this->role()->where('name', $role)->first();
    }


    public function address(){
        return $this->hasOne(Address::class, 'auditor_id');
    }

    public function profile(){
        return $this->hasOne(Profile::class, 'auditor_id');
    }

    public function company(){
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function confirmationRequest(){
        return $this->hasMany(ConfirmationRequest::class, 'auditor_id');
    }

}
