<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Permission extends Model
{
    use HasFactory;

    public const ADMIN_PERMISSION_NAME = "ADMIN_PERMISSION_NAME";
    public const USER_PERMISSION_NAME = "USER_PERMISSION_NAME";

    protected $table = 'permissions';

    public $incrementing = false;
    protected $primaryKey = 'permission_id';
    protected $keyType = 'string';

    protected $fillable = [
        'permission_id',
        'permission_name',
    ];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'permissions_roles', 'role_id', 'permission_id');
    }
}
