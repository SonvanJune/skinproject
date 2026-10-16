<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UrlRedirect extends Model
{
    use HasFactory;

    protected $table = 'url_redirects';

    protected $fillable = [
        'source_path',
        'target_path',
        'status_code',
        'hits',
        'last_accessed_at',
        'is_active',
    ];

    protected $casts = [
        'status_code' => 'integer',
        'hits' => 'integer',
        'last_accessed_at' => 'datetime',
        'is_active' => 'boolean',
    ];
}
