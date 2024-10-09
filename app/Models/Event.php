<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $primaryKey = 'event_id';


    public $incrementing = true;
    protected $keyType = 'int';


    protected $fillable = [
        'title',
        'start_date',
        'end_date',
        'category',
    ];
}
