<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;

    protected $primaryKey = 'payroll_id';

    protected $fillable = [
        'employee_id',
        // 'days_present',
        // 'total_leave',
        // 'effective_workdays',
        // 'current_salary',
        // 'total_salary',
        'validation_status'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }
    public function attandanceRecap()
    {
        return $this->hasOne(AttandanceRecap::class, 'employee_id', 'employee_id');
    }
    // Relasi ke model WorkdaySetting
    public function workdaySetting()
    {
        return $this->hasOne(WorkdaySetting::class, 'id'); // Sesuaikan foreign key jika berbeda
    }

    // Relasi ke model Overtime
    public function overtime()
    {
        return $this->hasMany(Overtime::class, 'employee_id', 'employee_id');
    }

      // Relasi ke model Offrequest
      public function offRequests()
      {
          return $this->hasMany(Offrequest::class, 'user_id', 'employee_id');
      }
}
