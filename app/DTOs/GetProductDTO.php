<?php

namespace App\DTOs;

use App\Models\Post;
use App\Models\Product;
use App\Models\User;
use App\Services\PostService;
use Illuminate\Support\Collection;

/**
 * Class GetProductDTO
 *
 * Data Transfer Object for retrieving product information.
 * This DTO encapsulates the data of a product, providing a structured way
 * to pass product data between layers of the application.
 */
class GetProductDTO
{
    /** @var string The id of the product */
    public string $product_id;

    /** @var string The name of the product */
    public string $product_name;

    /** @var string The price of the product */
    public float $product_price;

    /** @var string The file path of the product */
    public string $product_file_path;

    /** @var int The number of views for the product */
    public int $product_views;

    /** @var int The number of views for the product */
    public int $product_fake_views;

    /** @var int The number of status views for the product */
    public int $product_status_views;

    /** @var string The slug of the product */
    public string $product_slug;

    /** @var string The description of the product */
    public string $post_content;

    public string $post_id;

    /** @var string The name of the post */
    public ?string $post_name;

    /** @var string The image of the post */
    public ?string $post_image_path;

    /** @var string The image of the post */
    public ?string $post_image_alt;

    /** @var string The release of the post */
    public ?string $post_release;

    /** @var array The slug of the category */
    public array $category_slug;

    /** @var float The sale price of the product */
    public float $product_price_sale;

    /** @var array List ProductImage */
    public array $product_images;

    /** @var bool The can_download of product */
    public bool $can_download;

    /** @var bool The is_like of the Product */
    public bool $is_like;

    /** @var string The coupon of the product */
    public ?string $coupon_code;

    /**
     * GetProductDTO constructor.
     *
     * @param array $data An associative array containing product data
     */
    public function __construct(array $data)
    {
        $this->product_id = $data['product_id'];
        $this->product_name = $data['product_name'];
        $this->product_price = $data['product_price'];
        $this->product_file_path = $data['product_file_path'];
        $this->product_views = $data['product_views'];
        $this->product_fake_views = $data['product_fake_views'];
        $this->product_status_views = $data['product_status_views'];
        $this->product_slug = $data['product_slug'];
        $this->post_content = $data['post_content'];
        $this->post_id = $data['post_id'];
        $this->post_name = $data['post_name'];
        $this->post_image_path = $data['post_image_path'];
        $this->post_image_alt = $data['post_image_alt'];
        $this->post_release = $data['post_release'];
        $this->category_slug = $data['category_slug'];
        $this->product_price_sale = $data['product_price_sale'] ?? $data['product_price'];
        $this->product_images = $data['product_images'];
        $this->can_download = $data['can_download'];
        $this->is_like = $data['is_like'];
        $this->coupon_code = $data['coupon_code'];
    }

    /**
     * Create a GetProductDTO instance from a Product model.
     *
     * @param Product $product The Product model instance
     * @return self New instance of GetProductDTO
     */
    public static function fromModel(Product $product, ?GetUserDTO $user): self
    {
        return new self([
            'product_id' => $product->product_id,
            'product_name' => $product->product_name,
            'product_price' => $product->product_price,
            'product_file_path' => $product->product_file_path,
            'product_views' => $product->product_views,
            'product_fake_views' => $product->product_fake_views,
            'product_status_views' => $product->product_status_views,
            'product_slug' => $product->post->post_slug,
            'post_content' => $product->post->post_content,
            'post_name' => $product->post->post_name,
            'post_id' => $product->post->post_id,
            'post_image_path' => $product->post->post_image_path,
            'post_image_alt' => $product->post->post_image_alt,
            'post_release' => $product->post->post_release,
            'category_slug' => $product->categories->pluck('category_slug')->toArray(),
            'product_price_sale' => self::mathPriceSale($product),
            'product_images' => $product->productImages->all(),
            'can_download' => self::checkCanDownload($product, $user),
            'is_like' => self::checkIsLike($product, $user),
            'coupon_code' => self::getCouponCodeIfHave($product)
        ]);
    }

    /**
     * Create an array of GetProductDTO instances from an array of Product models.
     *
     * @param array $products An array of Product model instances
     * @return array An array of GetProductDTO instances
     */

    public static function fromModels(Collection $products, ?GetUserDTO $user)
    {
        $result = [];
        foreach ($products as $product) {
            if($product->post->post_status != Post::TYPE_POST_DELETE){
                $result[] = self::fromModel($product, $user);
            }
        }
        return $result;
    }

    public static function checkCanDownload(Product $product, ?GetUserDTO $user)
    {
        if ($user) {
            $userModel = User::find($user->user_id);
            foreach ($userModel->carts as $cart) {
                if ($cart->order && $cart->order->order_status == 2) {
                    foreach ($cart->products as $productInCart) {
                        if ($product->product_id === $productInCart->product_id) {
                            return true;
                        }
                    }
                }
            }
            return false;
        }
        return false;
    }

    public static function checkIsLike(Product $product, ?GetUserDTO $user)
    {
        if ($user) {
            $userModel = User::find($user->user_id);
            foreach ($userModel->products as $uproduct) {
                if ($uproduct->product_id === $product->product_id) {
                    return true;
                }
            }
            return false;
        }
        return false;
    }

    public static function mathPriceSale(Product $product)
    {
        $total = $product->product_price;
        if ($product->coupons()->exists()) {
            $coupons = $product->coupons()->get();
            foreach ($coupons as $coupon) {
                if ($coupon->product_id && !$coupon->coupon_code && now() >= $coupon->coupon_release && now() < $coupon->coupon_expired) {
                    $total = $coupon->coupon_price ? $product->product_price - $coupon->coupon_price : $product->product_price - ($product->product_price * $coupon->coupon_per_hundred / 100);
                }
            }
        }
        return $total;
    }

    public static function getCouponCodeIfHave(Product $product)
    {
        $coupon_code = null;
        if ($product->coupons()->exists()) {
            $coupons = $product->coupons()->get();
            foreach ($coupons as $coupon) {
                if ($coupon->product_id && $coupon->coupon_code && now() >= $coupon->coupon_release && now() < $coupon->coupon_expired) {
                    $coupon_code = $coupon->coupon_code;
                    if($coupon->coupon_price){
                        $coupon_code = $coupon_code . "( -$" .  $coupon->coupon_price . " )";
                    }
                    if($coupon->coupon_per_hundred){
                        $coupon_code = $coupon_code . "( -" .  $coupon->coupon_per_hundred . "% )";
                    }
                }
            }
        }
        return $coupon_code;
    }
}
