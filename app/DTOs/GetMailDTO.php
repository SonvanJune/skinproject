<?php

namespace App\DTOs;

class GetMailDTO
{
    //properties for mail
    public string $mail_id; 
    public string $mail_name;
    public string $mail_file_name;
    public string $content;
    public array $attributes;
    public array $required_attributes;

    public function __construct(
        string $mail_id,
        string $mail_name,
        string $mail_file_name,
        string $content = "",
        array $attributes = [],
        array $required_attributes = []
    ) {
        $this->mail_id = $mail_id;
        $this->mail_name = $mail_name;
        $this->mail_file_name = $mail_file_name;
        $this->content = $content;
        $this->attributes = $attributes;
        $this->required_attributes = $required_attributes;
    }
}
