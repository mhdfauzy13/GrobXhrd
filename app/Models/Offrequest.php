<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offrequest extends Model
{
    use HasFactory;


    protected $table = 'offrequests';


    protected $primaryKey = 'offrequest_id';

    public $incrementing = true;

    protected $fillable = [
        'name',
        'email',
        'mtitle',
        'description',
        'start_event',
        'end_event',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    protected $casts = [
        'start_event' => 'datetime',
        'end_event' => 'datetime',
    ];
}
