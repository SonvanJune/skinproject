<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderCoupon extends Model
{
    use HasFactory;

    protected $table = 'orders_coupons';

    public $incrementing = false;
    protected $primaryKey = ['order_id', 'coupon_id'];
    protected $keyType = 'string'; 

    protected $fillable = [
        'order_id',
        'coupon_id',
        'created_at',
    ];

    const CREATED_AT = 'created_at';

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class, 'coupon_id', 'coupon_id');
    }
}
