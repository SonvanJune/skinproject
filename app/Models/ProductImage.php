<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductImage extends Model
{
    use HasFactory;


    protected $table = 'product_images';

    public $incrementing = false;
    protected $primaryKey = 'product_image_id';
    protected $keyType = 'string'; 

    protected $fillable = [
        'product_image_id',
        'product_image_path',
        'product_image_alt',
        'product_id',
    ];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
