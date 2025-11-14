<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    public const CATEGORY_STATUS_DELETE = -1;
    public const CATEGORY_STATUS_INACTIVE = 0;
    public const CATEGORY_STATUS_ACTIVE = 1;
    public const CATEGORY_TYPE_DEFAULT = 1;
    public const CATEGORY_TYPE_BRAND = 2;

    protected $table = 'categories';

    public $incrementing = false;
    protected $primaryKey = 'category_id';
    protected $keyType = 'string';

    protected $fillable = [
        'category_id',
        'category_name',
        'category_slug',
        'category_status',
        'category_image_path',
        'category_image_alt',
        'parent_id',
        'category_type',
        'category_description',
        'category_topbar_index',
        'category_home_index',
        'category_release'
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'products_categories', 'category_id', 'product_id');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id', 'category_id');
    }

    public function childrens()
    {
        return $this->hasMany(Category::class,  'parent_id', 'category_id');
    }
    public function childrenRecursive()
    {
        return $this->childrens()->with('childrenRecursive');
    }
}
