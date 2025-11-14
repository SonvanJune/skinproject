<?php

namespace App\DTOs;

/**
 * Data Transfer Object (DTO) for representing a page of posts.
 */
class PaypalResponseDTO
{
    /** @var string The message*/
    public string $link;
    public int $status;
    public string $message = "";

    public function __construct(string $link, int $status, string $message = "")
    {
        $this->link = $link;
        $this->status = $status;
        $this->message = $message;
    }

    /**
     * Create an PaypalResponseDTO instance from a message.
     *
     * @param string $message The Coupon model instance.
     * @return self A new PaypalResponseDTO instance.
     */
    public static function fromModel(string $link, int $status, string $message = ""): self
    {
        return new self($link,$status,$message);
    }
}