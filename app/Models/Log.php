<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Log extends Model
{
    use HasFactory;

    protected $table = 'logs';

    public $incrementing = false;
    protected $primaryKey = 'log_id';
    protected $keyType = 'string'; 

    protected $fillable = [
        'log_id',
        'log_type',
        'log_action',
        'log_line',
        'log_url',
        'log_request',
        'log_response',
    ];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = null; 
}
