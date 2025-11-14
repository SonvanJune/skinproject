<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UserQuestion extends Model
{
    use HasFactory;

    protected $table = 'users_questions';

    public $incrementing = false;
    protected $primaryKey = ['user_id', 'question_id'];
    protected $keyType = 'string';

    protected $fillable = [
        'user_id',
        'question_id',
        'user_answer'
    ];

    public function user(): HasOne
    {
        return $this->hasOne(User::class, "user_id");
    }

    public function question(): HasOne
    {
        return $this->hasOne(Question::class, "question_id");
    }
}
