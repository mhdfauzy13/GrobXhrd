<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeBook extends Model
{
    use HasFactory;

    protected $table = 'employee_books';

    protected $primaryKey = 'employeebook_id';

    protected $fillable = [
        'employee_id',
        'incident_date',
        'incident_details',
        'remarks',
        'category',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
