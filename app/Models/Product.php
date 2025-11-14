<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;


    protected $table = 'products';

    protected $primaryKey = 'product_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'product_id',
        'product_name',
        'product_price',
        'product_file_path',
        'post_id',
        'coupon_id',
        'product_views'
    ];

    protected $casts = [
        'product_price' => 'decimal:2',
        'product_views' => 'integer'
    ];

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id', 'post_id');
    }

    public function coupons()
    {
        return $this->hasMany(Coupon::class, 'product_id', 'product_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'products_categories', 'product_id', 'category_id');
    }

    public function productImages()
    {
        return $this->hasMany(ProductImage::class, 'product_id', 'product_id');
    }
}
