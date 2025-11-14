<?php

namespace App\DTOs;

class ZipDTO
{
    public string $content;

    public function __construct(string $content)
    {
        $this->content = $content;
    }

    public static function create(string $content): self
    {
        return new self($content);
    }
}