<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $primaryKey = 'user_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'user_id',         
        'user_first_name',  
        'user_last_name',   
        'user_email',       
        'user_password',    
        'user_password_level_2',    
        'user_status',     
        'user_phone',      
        'user_birthday',     
        'user_avatar'     
    ];

    public function carts() : HasMany
    {
        return $this->hasMany(Cart::class, 'user_id', 'user_id');
    }

    public function products() : BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'wishlist', 'user_id', 'product_id');
    }

    public function roles() : BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'users_roles', 'user_id', 'role_id');
    }

    public function questions() : BelongsToMany
    {
        return $this->belongsToMany(Question::class, 'users_questions', 'user_id', 'question_id')
        ->withPivot("user_answer");
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [
            "email" => $this->user_email,
            "password" => $this->user_password,
        ];
    }
}