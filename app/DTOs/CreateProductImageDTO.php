<?php

namespace App\DTOs;

use App\Models\ProductImage;

class CreateProductImageDTO
{
    public string $product_image_path;
    public string $product_image_alt;
    public string $product_id;

    /**
     * CreateProductImageDTO constructor.
     *
     * @param string $product_image_path The path of the product image.
     * @param string $product_image_alt The alt text for the product image.
     * @param string $product_id The ID of the associated product.
     */
    public function __construct(string $product_image_path, string $product_image_alt, string $product_id)
    {
        $this->product_image_path = $product_image_path;
        $this->product_image_alt = $product_image_alt;
        $this->product_id = $product_id;
    }

    /**
     * Creates a new CreateProductImageDTO from a ProductImage model.
     *
     * @param ProductImage $productImage The ProductImage model instance.
     * @return self A new CreateProductImageDTO instance.
     */
    public static function fromModel(ProductImage $productImage): self
    {
        return new self(
            $productImage->product_image_path,
            $productImage->product_image_alt,
            $productImage->product_id
        );
    }
}
