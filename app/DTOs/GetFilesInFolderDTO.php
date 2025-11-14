<?php 
namespace App\DTOs;

use DateTime;

class GetFilesInFolderDTO{
    public string $name;
    public string $filePath;
    public string $size;
    public string $type;
    public string $mine;
    public DateTime $last_modified;

    public function __construct(array $data){
        $this->name = $data['name'] ?? 'unknown';
        $this->filePath = $data['filepath'] ?? '';
        $this->size = $data['size'] ?? 0;
        $this->type =$data['type'] ?? 'unknown';
        $this->mine =$data['mine'] ?? 'unknown';
        $this->last_modified = isset($data['last_modified'])
            ? new \DateTime($data['last_modified'])
            : new \DateTime();
    }

}