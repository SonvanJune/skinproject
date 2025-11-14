<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SlideshowImage extends Model
{
    use HasFactory;


    protected $table = 'slideshow_images';

    public $incrementing = false;
    protected $primaryKey = 'slideshow_image_id';
    protected $keyType = 'string';

    protected $fillable = [
        'slideshow_image_id',
        'slideshow_image_url',
        'slideshow_image_index',
        'slideshow_image_alt',
    ];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $casts = [
        'slideshow_image_index' => 'integer',
    ];
}
