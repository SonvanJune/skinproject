<?php

namespace App\DTOs;

use App\Models\Product;

/**
 * Class GetProductDTO
 *
 * Data Transfer Object for retrieving product information.
 * This DTO encapsulates the data of a product, providing a structured way
 * to pass product data between layers of the application.
 */
class RemoveProductInWishlistDTO
{
    /** @var string The name of the product */
    public string $product_name;

    /** @var string The price of the product */
    public string $product_price;

    /** @var string The file path of the product */
    public string $product_file_path;

    /** @var string The ID of the associated post */
    public string $post_id;

    /** @var int The number of views for the product */
    public int $product_views;

    /** @var int The number of fake views for the product */
    public int $product_fake_views;

    /** @var bool The status of product views */
    public bool $product_status_views;

    /** @var string The slug of the product */
    public string $product_slug;

    /** @var string The ID of the associated coupon */
    public string $coupon_id;

    /**
     * GetProductDTO constructor.
     *
     * @param array $data An associative array containing product data
     */
    public function __construct(array $data)
    {
        $this->product_name = $data['product_name'];
        $this->product_price = $data['product_price'];
        $this->product_file_path = $data['product_file_path'];
        $this->post_id = $data['post_id'];
        $this->product_views = $data['product_views'];
        $this->product_fake_views = $data['product_fake_views'];
        $this->product_status_views = $data['product_status_views'];
        $this->product_slug = $data['product_slug'];
        $this->coupon_id = $data['coupon_id'];
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
            'product_name' => $product->product_name,
            'product_price' => $product->product_price,
            'product_file_path' => $product->product_file_path,
            'post_id' => $product->post_id,
            'product_views' => $product->product_views,
            'product_fake_views' => $product->product_fake_views,
            'product_status_views' => $product->product_status_views,
            'product_slug' => $product->product_slug,
            'coupon_id' => $product->coupon_id
        ]);
    }
}
