<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Coupon extends Model
{
    use HasFactory;

    protected $table = 'coupons';

    protected $primaryKey = 'coupon_id';
    public $incrementing = false;
    protected $keyType = 'string';  

    protected $fillable = [
        'coupon_id',
        'coupon_name',
        'coupon_code',
        'coupon_release',
        'coupon_expired',
        'coupon_per_hundred',
        'coupon_price'
    ];

    protected $casts = [
        'coupon_release' => 'datetime',
        'coupon_expired' => 'datetime',
        'coupon_per_hundred' => 'double'
    ];

    /**
     * Get the product associated with the post.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function product()
    {
        return $this->hasOne(Product::class, 'product_id', 'product_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }
}
