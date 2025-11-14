<?php

namespace App\DTOs;

use App\Models\Coupon;

/**
 * Data Transfer Object for updating a coupon.
 */
class DeleteSlideImageDTO
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
