<?php

namespace App\Models;

use App\Models\Attandance; // Periksa apakah nama model benar
use App\Models\Payroll; 
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
        'check_in_time',
        'check_out_time',
        'place_birth',
        'date_birth',
        'identity_number',
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
        return $this->hasOne(User::class, 'email', 'email'); // Menghubungkan berdasarkan email
    }

    public function offrequests()
    {
    return $this->hasMany(Offrequest::class, 'user_id', 'user_id');
    }   

}
