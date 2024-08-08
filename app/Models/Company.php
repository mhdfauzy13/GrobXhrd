<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $primaryKey = 'company_id';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fillable = [
        'name_company',
        'address',
        'phone_number',
        'email',
        'status',
        'google_client_id',
        'google_client_secret',
        'google_oauth_scope',
        'google_json_file',
        'google_oauth_url',
    ];
}
