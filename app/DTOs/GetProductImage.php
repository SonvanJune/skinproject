<?php

namespace App\DTOs;

use Illuminate\Support\Collection;

class GetProductImage
{
    public string $product_image_path;
    public string $product_image_alt;
    public string $product_id;

    /**
     * GetProductImage constructor.
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
     * Creates an array of GetProductImage DTOs from an array of ProductImage models.
     *
     * @param array $productImages An array of ProductImage models.
     * @return array An array of GetProductImage DTOs.
     */
    public static function fromModels(Collection $productImages): array
    {
        $result = [];
        foreach ($productImages as $productImage) {
            $result[] = new self(
                $productImage->product_image_path,
                $productImage->product_image_alt,
                $productImage->product_id
            );
        }
        return $result;
    }
}
