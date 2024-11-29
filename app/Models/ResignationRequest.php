<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResignationRequest extends Model
{
    protected $table = 'resignation_requests';
    protected $primaryKey = 'resignationrequest_id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'user_id',
        'manager_id',
        'name',
        'resign_date',
        'reason',
        'remarks',
        'status',
        'document',
    ];
    protected $casts = [
        'resign_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id', 'user_id');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
    public function scopeForManager($query, $managerId)
    {
        return $query->where('manager_id', $managerId);
    }
}
