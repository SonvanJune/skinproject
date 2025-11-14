<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Model representing a Post.
 *
 * This model defines a post in the application, including its attributes,
 * relationships with other models, and data type casts.
 */
class Post extends Model
{
    use HasFactory;

    public const TYPE_PRODUCT = 1;
    public const TYPE_POST = 0;
    public const TYPE_POST_DELETE = -1;

    public const STATUS_RELEASE = 1;
    public const STATUS_EXPIRE = 0;

    public const PER_PAGE = 15;

    /**
     * @var string The table associated with the model.
     */
    protected $table = 'posts';

    /**
     * @var string The primary key of the model.
     */
    protected $primaryKey = 'post_id';

    /**
     * @var bool Indicates if the IDs are auto-incrementing.
     */
    public $incrementing = false;

    /**
     * @var string The type of the primary key ID.
     */
    protected $keyType = 'string';

    /**
     * @var array The attributes that are mass assignable.
     */
    protected $fillable = [
        'post_id',
        'post_name',
        'post_slug',
        'post_release',
        'post_status',
        'post_type',
        'user_id',
        'post_content',
        'post_image_path',
        'post_image_alt'
    ];

    /**
     * @var array The attributes' casting to native types.
     */
    protected $casts = [
        'post_release' => 'datetime',
        'post_status' => 'integer',
        'post_type' => 'integer'
    ];

    /**
     * Get the user that owns the post.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Get the product associated with the post.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function product()
    {
        return $this->hasOne(Product::class, 'post_id', 'post_id');
    }
}
