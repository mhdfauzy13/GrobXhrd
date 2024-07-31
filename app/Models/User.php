<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Menetapkan nama kolom primary key
    protected $primaryKey = 'user_id';

    // Jika menggunakan increment pada primary key
    public $incrementing = true;

    // Tipe data primary key
    protected $keyType = 'int';

    protected $hidden = [
        'password',
        // 'remember_token',
    ];
     // Menentukan kolom yang dapat diisi massal
    protected $fillable = [
        'name',
        'email',
        'role',
        'password',
    ];
    // protected function casts(): array
    // {
    //     return [
    //         'email_verified_at' => 'datetime',
    //         'password' => 'hashed',
    //     ];
    // }
}
