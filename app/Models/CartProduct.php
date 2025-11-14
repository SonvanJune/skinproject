<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CartProduct extends Model
{
    use HasFactory;

    protected $table = 'carts_products';

    
    public $incrementing = false;
    protected $primaryKey = ['cart_id', 'product_id'];
    protected $keyType = 'string'; 

    public $timestamps = false;

    protected $fillable = [
        'cart_id',
        'product_id'
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class, 'cart_id', 'cart_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
