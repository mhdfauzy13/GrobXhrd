<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeBook extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'incident_date',
        'incident_detail',
        'remarks',
        'category',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }
}
