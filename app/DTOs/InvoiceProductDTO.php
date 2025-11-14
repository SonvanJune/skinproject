<?php

namespace App\DTOs;

use App\Models\Product;

class InvoiceProductDTO
{
    //properties
    public string $product_name;
    public int $product_quantity;
    public float $product_price;

    /**
     * InvoiceProductDTO constructor.
     *
     * @param string $product_name
     * @param string $product_quantity
     * @param string $product_price
     */
    public function __construct(string $product_name, int $product_quantity, float $product_price)
    {
        $this->product_name = $product_name;
        $this->product_quantity = $product_quantity;
        $this->product_price = $product_price;
    }

    /**
     * Create a InvoiceProductDTO instance from a Product model.
     *
     * @param Product $product The Product model instance
     * @param int $product_quantity The quantity of product in the order
     * @return self New instance of InvoiceProductDTO
     */
    public static function fromModel(Product $product, int $product_quantity): self
    {
        return new self($product->product_name, $product->product_price, $product_quantity);
    }
}
