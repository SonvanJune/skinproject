<?php

namespace App\DTOs;

use App\Models\Category;
use App\Services\CategoryService;
use DateTime;
use Illuminate\Support\Collection;

class GetCategoryAdminDTO
{
    /** @var string The id of the category */
    public string $id;
    /** @var string The name of the category */
    public string $name;
    /** @var string The slug of the category */
    public string $slug;
    /** @var int The hierarchical level of the category */
    public ?int $level;
    /** @var string The path to the category image */
    public ?string $image_path;
    /** @var string The alt text for the category image */
    public ?string $image_alt;
    /** @var int The status of the category */
    public int $status;
    /** @var array The list of child categories as DTOs */
    public ?array $children = [];
    /** @var int The type of the category */
    public int $type;
    /** @var int The index for the topbar category */
    public ?int $topbar_index;
    /** @var int The index for the homepage category */
    public ?int $home_index;
    /** @var string The release date of the category */
    public string $release;
    /** @var int The number of products in the category */
    public ?int $product_count;
    /** @var int The number of children categories in the category */
    public ?int $children_category_count;
    /** @var string The description of the category */
    public ?string $category_description;
    /** @var string The parent slug of the category */
    public ?string $parent_slug;
    /**
     * GetCategoryDTO constructor.
     *
     * @param string $name The name of the category.
     * @param string $slug The slug of the category.
     * @param ?string $image_path The path to the category image.
     * @param ?string $image_alt The alt text for the category image.
     * @param int $status The status of the category.
     * @param ?int $level The hierarchical level of the category.
     * @param ?array $children The list of child categories as DTOs.
     * @param int $type The type of the category.
     * @param ?int $topbar_index The index for the topbar category.
     * @param ?int $home_index The index for the homepage category.
     * @param string $release The release date of the category.
     */
    public function __construct(
        string $id,
        string $name,
        string $slug,
        ?string $image_path,
        ?string $image_alt,
        int $status,
        ?int $level,
        ?array $children,
        int $type,
        ?int $topbar_index,
        ?int $home_index,
        string $release,
        ?int    $product_count,
        ?int    $children_category_count,
        ?string $category_description,
        ?string $parent_slug
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->slug = $slug;
        $this->image_path = $image_path;
        $this->image_alt = $image_alt;
        $this->status = $status;
        $this->level = $level;
        $this->children = $children;
        $this->type = $type;
        $this->topbar_index = $topbar_index;
        $this->home_index = $home_index;
        $this->release = $release;
        $this->product_count = $product_count;
        $this->children_category_count = $children_category_count;
        $this->category_description = $category_description;
        $this->parent_slug = $parent_slug;
    }

    /**
     * Create a GetCategoryDTO instance from a Category model.
     *
     * @param Category $category The category model instance.
     * @param array &$processedCategories A reference to the array tracking processed categories to avoid infinite loops.
     * @return GetCategoryDTO The DTO instance representing the category.
     */
    public static function fromModel(Category $category, array &$processedCategories = []): self|null
    {
        if($category){
            if (in_array($category->category_id, $processedCategories)) {
                return new self(
                    $category->category_id,
                    $category->category_name,
                    $category->category_slug,
                    $category->category_image_path,
                    $category->category_image_alt,
                    $category->category_status,
                    null,
                    [],
                    $category->category_type,
                    $category->category_topbar_index,
                    $category->category_home_index,
                    $category->category_release ? (new DateTime($category->category_release))->format('Y-m-d H:i:s') : now()->format('Y-m-d H:i:s'),
                    null,
                    null,
                    $category->category_description ?? null,
                    $category->parent? $category->parent->category_slug : null
                );
            }
    
            $processedCategories[] = $category->category_id;
    
            $levelDTO = CategoryService::getLevel($category);
    
            $product_count = CategoryService::getProductCount($category);
            $children_category_count = CategoryService::getChildrenCategoryCount($category);
            $childrenDTO = [];
            foreach ($category->childrens()->where('category_status', '!=' , Category::CATEGORY_STATUS_DELETE)->get() as $_category) {
                $childrenDTO[] = self::fromModel($_category, $processedCategories);
            }
    
            return new self(
                $category->category_id,
                $category->category_name,
                $category->category_slug,
                $category->category_image_path,
                $category->category_image_alt,
                $category->category_status,
                $levelDTO,
                $childrenDTO,
                $category->category_type,
                $category->category_topbar_index,
                $category->category_home_index,
                $category->category_release ? (new DateTime($category->category_release))->format('Y-m-d H:i:s') : now()->format('Y-m-d H:i:s'),
                $product_count,
                $children_category_count,
                $category->category_description ?? null,
                $category->parent? $category->parent->category_slug : null
            );
        }
        else{
            return null;
        }
    }

    /**
     * Create an array of GetCategoryDTO instances from an array of Category models.
     *
     * @param Category[] $categories The array of Category model instances.
     * @return GetCategoryDTO[] The array of GetCategoryDTO instances representing the categories.
     */
    public static function fromModels(Collection $categories, $getAll): array|null
    {
        if($categories){
            $categoryDTOs = [];

            if ($getAll === false) {
                foreach ($categories as $category) {
                    if ($category->parent_id === null) {
                        $categoryDTOs[] = self::fromModel($category);
                    }
                }
            } else {
                foreach ($categories as $category) {
                    $categoryDTOs[] = self::fromModel($category);
                }
            }
    
            return $categoryDTOs;
        }
        else{
            return null;
        }
    }
}
