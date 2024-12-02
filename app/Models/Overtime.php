<?php

// app/Models/Overtime.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Overtime extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'overtime_date', 'duration', 'notes', 'status'];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
    public function employee()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
   // Model Overtime
public function manager() {
    return $this->belongsTo(User::class, 'manager_id'); // Relasi manager ke model User
}
}
