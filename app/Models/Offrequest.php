<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Offrequest extends Model
{
    protected $table = 'offrequests';
    protected $primaryKey = 'offrequest_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['name', 'email', 'title', 'description', 'start_event', 'end_event', 'user_id', 'employee_id', 'manager_id', 'status', 'approver_id', 'image'];

    protected $casts = [
        'start_event' => 'datetime',
        'end_event' => 'datetime',
    ];

    // Relasi ke User sebagai pengaju cuti
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke User sebagai manager yang menyetujui
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    // Relasi ke User sebagai approver
    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    // Scope untuk mendapatkan offrequest dengan status pending
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    // Scope untuk mendapatkan offrequest berdasarkan manager
    public function scopeForManager(Builder $query, $managerId): Builder
    {
        return $query->where('manager_id', $managerId);
    }

    // Scope untuk memeriksa apakah employee sedang cuti pada tanggal tertentu
    public function scopeIsOnLeave(Builder $query, $employeeId, $date): Builder
    {
        return $query->where('user_id', $employeeId)->where('status', 'approved')->whereDate('start_event', '<=', $date)->whereDate('end_event', '>=', $date);
    }
}
