<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Wishlist extends Model
{
    use HasFactory;

    protected $table = 'wishlist';

    public $incrementing = false;
    protected $primaryKey = ['user_id', 'product_id'];
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'product_id',
    ];

    public function user()
    {
        try {
            $user = $this->belongsTo(User::class)->firstOrFail();
            return $user;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function product()
    {
        try {
            $product = $this->belongsTo(Product::class)->firstOrFail();
            return $product;
        } catch (\Exception $e) {
            return null;
        }
    }
}
