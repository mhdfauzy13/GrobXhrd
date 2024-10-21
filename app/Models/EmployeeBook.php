<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeBook extends Model
{
    protected $table = 'employee_books';
    protected $primaryKey = 'employeebook_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'employee_id',
        'incident_date',
        'incident_detail',
        'remarks',
        'category'
    ];


    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }
}
