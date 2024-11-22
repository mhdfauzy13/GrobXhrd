<?php

namespace App\Models;

use App\Models\Attandance; // Pastikan nama model sudah benar
use App\Models\Payroll;
use App\Models\Overtime; // Menambahkan relasi ke model Overtime
use App\Models\WorkdaySetting; // Menambahkan relasi ke WorkdaySetting jika diperlukan
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $table = 'employees'; // Nama tabel

    protected $primaryKey = 'employee_id'; // Primary key jika berbeda dari default 'id'

    protected $keyType = 'int'; // Tipe primary key

    public $incrementing = true; // Auto increment

    public $timestamps = true; // Menyimpan timestamp

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'check_in_time',
        'check_out_time',
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
        'status',
        'resign_date',
        'reason',
        'remarks',
        'document',
    ];

    protected $hidden = []; // Kolom yang tidak ingin ditampilkan (opsional)

    protected $casts = [
        'insurance' => 'boolean', // Untuk konversi tipe data boolean
        'current_salary' => 'decimal:2', // Untuk memastikan salary adalah tipe decimal dengan 2 desimal
    ];


    // public function user()
    // {
    //     return $this->belongsTo(User::class, 'user_id');
    // }

    public function user()
    {
        return $this->hasOne(User::class, 'employee_id');
    }


    // Relasi dengan Attendance
    public function attendances(): HasMany
    {
        return $this->hasMany(Attandance::class, 'employee_id', 'employee_id');
    }

    public function attendancerecaps(): HasMany

    {
        return $this->hasMany(AttandanceRecap::class, 'employee_id', 'employee_id');
    }

    // Relasi dengan Payroll
    public function payrolls(): HasMany
    {
        return $this->hasMany(Payroll::class, 'employee_id', 'employee_id');
    }

    // Relasi dengan Overtime
    public function overtimes(): HasMany
    {
        return $this->hasMany(Overtime::class, 'employee_id', 'employee_id');
    }

    // Relasi dengan WorkdaySetting (Jika ada)
    public function workdaySetting(): HasOne
    {
        return $this->hasOne(WorkdaySetting::class, 'employee_id', 'employee_id');
    }
}
