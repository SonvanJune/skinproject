<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TrackingCode extends Model
{
    use HasFactory;


    protected $table = 'tracking_codes';

    public $incrementing = false;
    protected $primaryKey = 'tracking_code_id';
    protected $keyType = 'string';

    protected $fillable = [
        'tracking_code_id',
        'tracking_code',
        'tracking_code_type',
    ];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $casts = [
        'tracking_code_type' => 'integer',
    ];
}
