<?php

namespace App\DTOs;

use App\Models\Category;
use App\Services\CategoryService;
use DateTime;

class UpdateCategoryDTO
{
    public string $name;
    public string $slug;
    public ?string $image_path;
    public ?string $image_alt;
    public int $status;
    public ?UpdateCategoryDTO $parent;
    public int $type;
    public ?int $topbar_index;
    public ?int $home_index;
    public ?int $level;
    public string $release;

    /**
     * UpdateCategoryDTO constructor.
     *
     * @param string $name The name of the category.
     * @param string $slug The slug of the category.
     * @param ?string $image_path The path to the category image, if any.
     * @param ?string $image_alt The alt text for the category image, if any.
     * @param int $status The status of the category (e.g., active, inactive).
     * @param ?UpdateCategoryDTO|null $parent The parent category as a DTO, if any.
     * @param int $type The type of the category.
     * @param ?int $topbar_index The index for the topbar category, if any.
     * @param ?int $home_index The index for the homepage category, if any.
     * @param ?int $level The hierarchical level of the category.
     * @param string $release The release date of the category in 'Y-m-d H:i:s' format.
     */
    public function __construct(
        string $name,
        string $slug,
        ?string $image_path,
        ?string $image_alt,
        int $status,
        ?UpdateCategoryDTO $parent,
        int $type,
        ?int $topbar_index,
        ?int $home_index,
        ?int $level,
        string $release
    ) {
        $this->name = $name;
        $this->slug = $slug;
        $this->image_path = $image_path;
        $this->image_alt = $image_alt;
        $this->status = $status;
        $this->parent = $parent;
        $this->type = $type;
        $this->topbar_index = $topbar_index;
        $this->home_index = $home_index;
        $this->level = $level;
        $this->release = $release;
    }

    /**
     * Create an UpdateCategoryDTO instance from a Category model.
     *
     * @param Category $category The category model instance.
     * @return self The DTO instance representing the category.
     */
    public static function fromModel(Category $category): self
    {
        $parentDTO = null;
        $levelDTO = 0;

        $levelDTO = CategoryService::getLevel($category);

        if ($category->parent_id) {
            $parentDTO = self::fromModel($category->parent);
        }

        return new self(
            $category->category_name,
            $category->category_slug,
            $category->category_image_path ?? null,
            $category->category_image_alt ?? null,
            $category->category_status,
            $parentDTO,
            $category->category_type,
            $category->category_topbar_index ?? null,
            $category->category_home_index ?? null,
            $levelDTO,
            $category->category_release ? (new DateTime($category->category_release))->format('Y-m-d H:i:s') : now()->format('Y-m-d H:i:s')
        );
    }
}
