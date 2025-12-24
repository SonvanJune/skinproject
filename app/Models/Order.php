<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    public $incrementing = false;
    protected $primaryKey = ['order_id', 'cart_id'];
    protected $keyType = 'string';

    protected $fillable = [
        'order_id',
        'order_status',
        'created_at',
        'updated_at',
        'cart_id',
        'order_payment',
        'vat_detail',
        'vat_value',
    ];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public function cart()
    {
        return $this->belongsTo(Cart::class, 'cart_id', 'cart_id');
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class, 'coupon_id', 'coupon_id');
    }
}
