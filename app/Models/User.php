<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles, Notifiable;

    protected $primaryKey = 'user_id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $hidden = ['password'];

    protected $fillable = ['name', 'email', 'password', 'employee_id'];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function employee()
    {
        return $this->hasOne(Employee::class, 'employee_id');
    }

    public function offrequests()
    {
        return $this->hasMany(Offrequest::class, 'user_id');
    }
    public function offrequestsAsManager()
    {
        return $this->hasMany(Offrequest::class, 'manager_id');
    }

    public function offrequestsAsEmployee()
    {
        return $this->hasMany(Offrequest::class, 'user_id');
    }

    public function approvedOffrequests()
    {
        return $this->hasMany(Offrequest::class, 'approver_id');
    }
}
