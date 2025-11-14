<?php

namespace App\DTOs;

use App\Models\OneTimePassword;
use App\Models\Post;
use App\Models\User;
use DateTime;

/**
 * Data Transfer Object for creating a post.
 */
class DeleteOTPDTO
{
    public string $one_time_password_id;
    public string $one_time_password_code;
    public int $one_time_password_type;
    public string $created_at;

    /**
     * CreatePostDTO constructor.
     *
     * @param array $data Associative array containing post data.
     */
    public function __construct(string $one_time_password_id, string $one_time_password_code, int $one_time_password_type, string $created_at)
    {
        $this->one_time_password_id = $one_time_password_id;
        $this->one_time_password_code = $one_time_password_code;
        $this->one_time_password_type = $one_time_password_type;
        $this->created_at = $created_at;
    }

    /**
     * Create an instance of CreatePostDTO from a Post model.
     *
     * @param Post $post The Post model instance.
     * @return self
     */
    public static function fromModel(OneTimePassword $one_time_password): self
    {
        return new self(
            $one_time_password->one_time_password_id,
            $one_time_password->one_time_password_code,
            $one_time_password->one_time_password_type,
            $one_time_password->created_at
        );
    }
}
