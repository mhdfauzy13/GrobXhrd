<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;
    protected $primaryKey = 'payroll_id'; 

    protected $fillable = [
        'employee_id', 'employee_name', 'days_present', 'total_leave', 
        'effective_work_days', 'current_salary', 'total_salary', 'validation_status'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
}
