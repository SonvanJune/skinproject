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
class InsertProductToWishlistDTO
{
    public string $message;

    public function __construct(string $message)
    {
        $this->message = $message;
    }

    public static function fromModel(string $message): self
    {
        return new self($message);
    }
}
