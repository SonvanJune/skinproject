<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OneTimePassword extends Model
{
    use HasFactory;

    protected $table = 'one_time_passwords';

    public $incrementing = false;
    protected $primaryKey = 'one_time_password_id';
    protected $keyType = 'string';

    protected $fillable = [
        'one_time_password_id',
        'one_time_password_code',
        'created_at',
        'user_id',
    ];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
