<?php

namespace App\DTOs;

use App\Models\Product;

/**
 * Class UpdateProductDTO
 *
 * Data Transfer Object for updating a product.
 * This DTO encapsulates the data required to update a product,
 * providing a structured way to pass product data between layers of the application.
 */
class UpdateProductDTO
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
