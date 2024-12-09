<?php

// app/Models/Overtime.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Overtime extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'overtime_date', 'duration', 'notes', 'status'];


    // Model Overtime
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    

    // Model User
    // public function employee()
    // {
    //     return $this->belongsTo(Employee::class, 'employee_id');
    // }

    // Model Overtime
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id'); // Relasi manager ke model User
    }
}
