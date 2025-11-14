<?php

namespace App\DTOs;

class RemoveFileDTO
{
    public bool $status;

    public string $message;

    public function __construct(array $data)
    {
        $this->status = $data['status'] ?? false;
        $this->message = $data['message'] ?? "not found error!";
    }
}
