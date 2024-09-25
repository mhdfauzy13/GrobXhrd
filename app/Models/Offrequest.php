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
        'approver_id',
    ];

    protected $casts = [
        'start_event' => 'datetime',
        'end_event' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function approver()
{
    return $this->belongsTo(User::class, 'approver_id');
}


// Menyaring offrequest yang masih pending
public function scopePending($query)
{
    return $query->where('status', 'pending');
}

// Menyaring offrequest berdasarkan manager
public function scopeForManager($query, $managerId)
{
    return $query->where('manager_id', $managerId);
}
}