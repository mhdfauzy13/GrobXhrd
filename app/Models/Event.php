<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    // Menggunakan event_id sebagai primary key
    protected $primaryKey = 'event_id';
    public $incrementing = true; // Atur sesuai kebutuhan
    protected $keyType = 'int'; // Atur sesuai kebutuhan

    // Jika menggunakan Route Model Binding dengan event_id
    public function getRouteKeyName()
    {
        return 'event_id';
    }

    // Daftar atribut yang bisa diisi secara massal
    protected $fillable = [
        'title',
        'start_date',
        'end_date',
        'category',
    ];
}
