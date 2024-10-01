<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles, HasFactory, Notifiable;

    protected $primaryKey = 'user_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $hidden = ['password'];

    protected $fillable = ['name', 'email', 'password'];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

   // Relasi ke Offrequest, di mana user ini bisa menjadi manager dari banyak Offrequest
   public function offrequestsAsManager()
   {
       return $this->hasMany(Offrequest::class, 'manager_id');
   }

   // Relasi ke Offrequest, di mana user ini mengajukan banyak cuti
   public function offrequestsAsEmployee()
   {
       return $this->hasMany(Offrequest::class, 'user_id');
   }

    public function approvedOffrequests()
    {
        return $this->hasMany(Offrequest::class, 'approver_id');
    }
}
