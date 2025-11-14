<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserRole extends Model
{
    use HasFactory;

    protected $table = 'users_roles';

    public $incrementing = false;
    protected $primaryKey = ['user_id', 'role_id'];
    protected $keyType = 'string';

    protected $fillable = [
        'user_id',
        'role_id',
    ];

    public function user()
    {
        return $this->hasOne(User::class, "user_id");
    }

    public function role()
    {
        return $this->hasOne(Role::class, "role_id");
    }
}
