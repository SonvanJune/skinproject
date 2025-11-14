<?php

namespace App\DTOs;

use App\Models\Post;
use DateTime;

/**
 * Data Transfer Object (DTO) for representing an updated post.
 */
class UpdatePostDTO
{
    /**
     * @var string|null The name of the post. Can be null.
     */
    public ?string $name;

    /**
     * @var string The slug of the post. Cannot be null.
     */
    public string $slug;

    /**
     * @var DateTime|null The release date of the post. Can be null.
     */
    public ?DateTime $release;

    /**
     * @var bool The status of the post. Default is boolean.
     */
    public bool $status;

    /**
     * @var int The type of the post. Default is integer.
     */
    public int $type;

    /**
     * @var string The author of the post.
     */
    public string $author;

    /**
     * @var string The path to the post's image.
     */
    public string $image_path;

    /**
     * @var string The alt text for the post's image.
     */
    public string $image_alt;

    /**
     * Create a new UpdatePostDTO instance from an array of data.
     *
     * @param array $data The data to initialize the DTO.
     */
    public function __construct(array $data)
    {
        $this->name = $data['name'] ?? null;
        $this->slug = $data['slug'] ?? '';
        $this->release = isset($data['release']) ? new DateTime($data['release']) : null;
        $this->status = $data['status'] ?? false;
        $this->type = $data['type'] ?? 0;
        $this->author = $data['author'] ?? '';
        $this->image_path = $data['image_path'] ?? '';
        $this->image_alt = $data['image_alt'] ?? '';
    }

    /**
     * Create an UpdatePostDTO instance from a Post model.
     *
     * @param Post $post The Post model instance.
     * @return self The UpdatePostDTO instance.
     */
    public static function fromModel(Post $post): self
    {
        $author = $post->user()->get(['user_first_name', 'user_last_name'])->first();
        $post_author = $author->user_first_name . " " . $author->user_last_name;

        return new self([
            'name' => $post->post_name,
            'slug' => $post->post_slug,
            'release' => $post->post_release ? $post->post_release->format('Y-m-d H:i:s') : null,
            'status' => $post->post_status,
            'type' => $post->post_type,
            'author' => $post_author,
            'image_path' => $post->post_image_path,
            'image_alt' => $post->post_image_alt,
        ]);
    }
}
