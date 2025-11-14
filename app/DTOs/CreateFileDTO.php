<?php

namespace App\DTOs;

use DateTime;

class CreateFileDTO
{
    public string $name;
    public float $size;
    public string $unitSize;
    public string $path;
    public string $link;
    public DateTime $date_modified;

    //constructor
    public function __construct(array $data)
    {
        $this->name = $data['name'] ?? '';
        $this->size = $data['size'] ?? 0;
        $this->unitSize = $data['unitSize'] ?? 'B';
        $this->path = $data['path'] ?? '';
        $this->link = $data['link'] ?? '';
        $this->date_modified = isset($data['date_modified'])
            ? new \DateTime($data['date_modified'])
            : new \DateTime();
    }
}
