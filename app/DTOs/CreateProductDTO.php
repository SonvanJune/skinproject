<?php

namespace App\DTOs;

use App\Models\Product;

/**
 * Class CreateProductDTO
 *
 * Data Transfer Object for creating a new product.
 * This DTO encapsulates the data required to create a new product,
 * providing a structured way to pass product data between layers of the application.
 */
class CreateProductDTO
{
    /** @var string The name of the product */
    public string $product_name;

    /** @var float The price of the product */
    public float $product_price;

    /** @var string The file path of the product */
    public string $product_file_path;

    /** @var string The ID of the associated post */
    public string $description;

    /** @var int The number of views for the product */
    public int $product_views;

    /** @var int The number of fake views for the product */
    public int $product_fake_views;

    /** @var bool The status of product views */
    public bool $product_status_views;

    /** @var int The discount of the product */
    public ?int $product_discount;
    /**
     * CreateProductDTO constructor.
     *
     * @param array $data An associative array containing product data
     */
    public function __construct(array $data)
    {
        $this->product_name = $data['product_name'];
        $this->product_price = $data['product_price'];
        $this->product_file_path = $data['product_file_path'];
        $this->description = $data['description'];
        $this->product_views = $data['product_views'];
        $this->product_fake_views = $data['product_fake_views'];
        $this->product_status_views = $data['product_status_views'];
        $this->product_discount = $data['product_discount'];
    }

    /**
     * Create a CreateProductDTO instance from a Product model.
     *
     * @param Product $product The Product model instance
     * @return self New instance of CreateProductDTO
     */
    public static function fromModel(Product $product): self
    {
        return new self([
            'product_name' => $product->product_name,
            'product_price' => $product->product_price,
            'product_file_path' => $product->product_file_path,
            'description' => $product->post->post_content,
            'product_views' => $product->product_views,
            'product_fake_views' => $product->product_fake_views,
            'product_status_views' => $product->product_status_views,
            'product_discount' => $product->coupon ? $product->coupon->coupon_per_hundred ? $product->coupon->coupon_per_hundred : $product->coupon->coupon_price : null,
        ]);
    }
}
