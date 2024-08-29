<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offrequest extends Model
{
    protected $fillable = [
        'name',
        'email',
        'title',
        'description',
        'start_event',
        'end_event',
        'user_id',
        'manager_id',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
}
