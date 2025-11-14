<?php

namespace App\DTOs;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use App\Services\CartService;
use DateTime;
use Illuminate\Support\Collection;

/**
 * Class GetProductDTO
 *
 * Data Transfer Object for retrieving product information.
 * This DTO encapsulates the data of a product, providing a structured way
 * to pass product data between layers of the application.
 */
class GetProductAdminDTO
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

    /** @var string The image of the post */
    public ?string $post_image_path;

    /** @var string The image of the post */
    public ?string $post_image_alt;

    /** @var string The status of the post */
    public string $post_status;

    /** @var string The release of the post */
    public string $post_release;

    /** @var array The categories */
    public array $categories;

    /** @var float The sale price of the product */
    public float $product_price_sale;

    /** @var string The discount of the product */
    public string $product_discount;

    /** @var GetCouponDTO The discount of the product */
    public ?GetCouponDTO $discount;

    /** @var array List ProductImage */
    public array $product_images;

    /** @var string The coupon of the product */
    public string $coupon_detail;

    /** @var string The updated of the product */
    public string $updated_at;

    /** @var bool The status sold of the product */
    public bool $is_sold;

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
        $this->post_image_path = $data['post_image_path'];
        $this->post_image_alt = $data['post_image_alt'];
        $this->post_status = $data['post_status'];
        $this->post_release = $data['post_release'];
        $this->categories = $data['categories'];
        $this->product_price_sale = $data['product_price_sale'] ?? $data['product_price'];
        $this->product_discount = $data['product_discount'];
        $this->discount = $data['discount'];
        $this->product_images = $data['product_images'];
        $this->coupon_detail = $data['coupon_detail'];
        $this->updated_at = $data['updated_at'];
        $this->is_sold = $data['is_sold'];
    }

    /**
     * Create a GetProductDTO instance from a Product model.
     *
     * @param Product $product The Product model instance
     * @return self New instance of GetProductDTO
     */
    public static function fromModel(Product $product): self
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
            'post_id' => $product->post->post_id,
            'post_image_path' => $product->post->post_image_path,
            'post_image_alt' => $product->post->post_image_alt,
            'post_status' => $product->post->post_status,
            'post_release' => (new DateTime($product->post->post_release))->format('Y-m-d H:i:s'),
            'categories' => $product->categories->map(function ($category) {
                return [
                    'slug' => $category->category_slug,
                    'name' => $category->category_name,
                    'image_path' => $category->category_image_path,
                    'image_alt' => $category->category_image_alt,
                ];
            })->toArray(),
            'product_price_sale' => self::mathPriceSale($product),
            'product_discount' => self::mathDiscount($product),
            'discount' => self::mathDiscount($product, true),
            'product_images' => $product->productImages->all(),
            'coupon_detail' => self::createCouponDetail($product),
            'updated_at' => $product->updated_at,
            'is_sold' => self::checkIsSold($product)
        ]);
    }

    /**
     * Create an array of GetProductDTO instances from an array of Product models.
     *
     * @param array $products An array of Product model instances
     * @return array An array of GetProductDTO instances
     */

    public static function fromModels(Collection $products)
    {
        $result = [];
        foreach ($products as $product) {
            $result[] = self::fromModel($product);
        }
        return $result;
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

    public static function mathDiscount(Product $product , $getDto = false)
    {
        $discount = "";
        if ($product->coupons()->exists()) {
            $coupons = $product->coupons()->get();
            foreach ($coupons as $coupon) {
                if ($coupon->product_id && !$coupon->coupon_code && now() >= $coupon->coupon_release && now() < $coupon->coupon_expired) {
                    if($getDto == true){
                        return GetCouponDTO::fromModel($coupon);
                    }
                    $discount = $coupon->coupon_price ? "- " . "$" . $coupon->coupon_price : "- " . $coupon->coupon_per_hundred . "%";
                }
            }
        }
        if($getDto == true){
            return null;
        }
        return $discount;
    }

    public static function createCouponDetail(Product $product)
    {
        $couponDetail = "";
        if ($product->coupons()->exists()) {
            $coupons = $product->coupons()->get();
            foreach ($coupons as $coupon) {
                if ($coupon->product_id && $coupon->coupon_code && now() >= $coupon->coupon_release && now() < $coupon->coupon_expired) {                    
                    $couponDetail = $coupon->coupon_code . "/";
                    $discount = $coupon->coupon_price ? "- " .  "$" . $coupon->coupon_price : "- " . $coupon->coupon_per_hundred . "%";
                    $couponDetail = $couponDetail . "(" . $discount . ")";
                    return $couponDetail;
                }
            }
        }
        return $couponDetail;
    }

    public static function checkIsSold(Product $product)
    {
        $carts = Cart::where('cart_status', CartService::STATUS_BOUGHT)->get();
        foreach ($carts as $cart) {
            foreach ($cart->products as $p) {
                if ($product->product_id == $p->product_id) {
                    return true;
                }
            }
        }
        return false;
    }
}
