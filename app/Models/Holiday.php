<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    use HasFactory;

    protected $primaryKey = 'holiday_id';
    protected $fillable = ['name', 'date', 'description', 'color']; // Menambahkan 'color' ke fillable
}
