<?php

namespace App\DTOs;

use App\Models\Post;
use App\Services\UserService;
use DateTime;

/**
 * Data Transfer Object (DTO) for representing a page of posts.
 */
class PostPageDTO
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
     * @var string The author of the post.
     */
    public string $author;

    /**
     * @var string The content of the post.
     */
    public string $content;

    /**
     * @var string The path to the post's image.
     */
    public string $image_path;

    /**
     * @var string The alt text for the post's image.
     */
    public string $image_alt;

    /**
     * @var string The status post.
     */
    public int $status;

    /**
     * @var DateTime|null The update date of the post. Can be null.
     */
    public ?DateTime $updated_at;

    /**
     * Create a new PostPageDTO instance from an array of data.
     *
     * @param array $data The data to initialize the DTO.
     */
    public function __construct(array $data)
    {
        $this->name = $data['name'] ?? null;
        $this->slug = $data['slug'] ?? '';
        $this->release = isset($data['release']) ? new DateTime($data['release']) : null;
        $this->author = $data['author'] ?? '';
        $this->content = $data['content'] ?? '';
        $this->image_path = $data['image_path'] ?? '';
        $this->image_alt = $data['image_alt'] ?? '';
        $this->status = $data['status'];
        $this->updated_at = $data['updated_at'];
    }

    /**
     * Create a PostPageDTO instance from a Post model.
     *
     * @param Post $post The Post model instance.
     * @return self The PostPageDTO instance.
     */
    public static function fromModel(Post $post, UserService $userService): self
    {
        // Retrieve the author's full name from the Post model
        $user = $userService->decryptUser($post->user()->first());
        $post_author = $user->user_first_name . " " . $user->user_last_name;

        return new self([
            'name' => $post->post_name,
            'slug' => $post->post_slug,
            'release' => $post->post_release ? $post->post_release->format('Y-m-d H:i:s') : null,
            'author' => $post_author,
            'content' => $post->post_content,
            'image_path' => $post->post_image_path,
            'image_alt' => $post->post_image_alt,
            'status' => $post->post_status,
            'updated_at' => $post->updated_at,
        ]);
    }

    /**
     * Create an array of PostPageDTO instances from a collection of Post models.
     *
     * @param iterable $posts A collection of Post models.
     * @return PostPageDTO[] An array of PostPageDTO instances.
     */
    public static function fromListModels($posts, UserService $userService): array
    {
        $result = [];

        foreach ($posts as $post) {
            $result[] = self::fromModel($post, $userService);
        }

        return $result;
    }
}
