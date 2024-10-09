<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;
    protected $fillable = [
        'employee_id',
        'allowance',
        'overtime',
        'deductions',
        'total_salary',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
