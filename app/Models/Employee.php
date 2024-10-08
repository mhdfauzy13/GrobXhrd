<?php

namespace App\Models;

use App\Models\Attendance; // Periksa apakah nama model benar
use App\Models\Payroll; // Tambahkan import untuk Payroll
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $table = 'employees';

    protected $primaryKey = 'employee_id';

    protected $keyType = 'int';

    public $incrementing = true;

    public $timestamps = true;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'place_birth',
        'date_birth',
        'personal_no',
        'address',
        'current_address',
        'blood_type',
        'blood_rhesus',
        'phone_number',
        'hp_number',
        'marital_status',
        'last_education',
        'degree',
        'starting_date',
        'interview_by',
        'current_salary',
        'insurance',
        'serious_illness',
        'hereditary_disease',
        'emergency_contact',
        'relations',
        'emergency_number',
        'status'
    ];

    protected $hidden = [];
    
    protected $casts = [
        'insurance' => 'boolean',
        'current_salary' => 'integer',
    ];

    public function attendances(): HasMany
    {
        return $this->hasMany(Attandance::class, 'employee_id', 'employee_id');
    }

    public function payrolls(): HasMany // Menambahkan tipe kembalian
    {
        return $this->hasMany(Payroll::class, 'employee_id', 'employee_id'); // Pastikan ada relasi yang tepat
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
