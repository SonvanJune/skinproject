<?php

namespace App\DTOs;


class DownloadProductDTO
{
    public string $zip_content;
    public int $status;
    public array $headers;

    public function __construct(string $zip_content , int $status, array $headers)
    {
        $this->zip_content = $zip_content;
        $this->status = $status;
        $this->headers = $headers;
    }

    public static function create(string $zip_content , int $status, array $headers): self
    {
        return new self($zip_content, $status, $headers);
    }
}