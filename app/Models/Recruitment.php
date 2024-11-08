<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recruitment extends Model
{
    use HasFactory;

    protected $table = 'recruitments';

    protected $primaryKey = 'recruitment_id';
    public $incrementing = true;

    // Menambahkan 'apply_position' ke dalam $fillable
    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'date_of_birth',
        'last_education',
        'last_position',
        'apply_position', // Menambahkan kolom apply_position
        'cv_file',
        'comment',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];
}
