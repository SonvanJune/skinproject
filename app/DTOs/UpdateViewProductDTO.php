<?php

namespace App\DTOs;

class UpdateViewProductDTO
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