<?php

namespace App\Services;

use App\DTOs\CreateCategoryDTO;
use App\DTOs\GetCategoryAdminDTO;
use App\DTOs\GetCategoryDTO;
use App\DTOs\PaginatedDTO;
use App\DTOs\UpdateCategoryDTO;
use App\Models\Category;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategoryService
{
    public const NO_SORT = 0;
    public const SORT_BY_TOPBAR_INDEX = 1;
    public const SORT_BY_HOME_INDEX = 2;
    public const ROLE_ADMIN = 0;
    public const ROLE_USER = 1;
    public const PAGE_SIZE_DEFAULT = 1;
    public const PER_PAGE_DEFAULT = 15;
    public const TRACKING_CODE_PATH_CSS = 'css/tracking_css_00.css';
    public const TRACKING_CODE_PATH_JS = 'js/tracking_js_00.css';


    /**
     * Creates a new category based on the provided request data.
     *
     * @param \Illuminate\Http\Request $request The HTTP request containing category data.
     * @return CreateCategoryDTO|string A DTO representing the created category on success, or an error message on failure.
     */
    public function createCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_name' => 'required|string',
            'category_slug' => 'required|string',
            'category_image_path' => 'nullable|string',
            'category_image_alt' => 'nullable|string',
            'category_status' => 'nullable|numeric',
            'parent_slug' => 'nullable|string',
            'category_description' => 'nullable|string',
            'category_type' => 'required|numeric',
            'category_release' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        if ($request->input('parent_slug')) {
            $parent = Category::where('category_slug', '=', $request->input('parent_slug'))->first();
            if (!$parent) {
                return 'Parent category not found';
            }
        }

        if ($request->input('category_slug')) {
            $slug = Category::where('category_slug', $request->input('category_slug'))->first();
            if ($slug) {
                return 'Slug already exists';
            }
        }
        $checkTypeCategoryWithImageError = $this->checkTypeCategoryMustHaveImage($request);
        if ($checkTypeCategoryWithImageError) {
            return $checkTypeCategoryWithImageError;
        }

        if ($request->input('category_image_path') && !file_exists(base_path($request->input('category_image_path')))) {
            return 'Image path does not exist';
        }

        if ($request->input('category_release') && $request->input('category_release') < now()) {
            return 'Release date must be today or in the future';
        }

        DB::beginTransaction();
        try {
            $category = new Category();
            $category->category_id = Str::uuid()->toString();
            $category->category_name = $request->input('category_name');
            $category->category_slug = $request->input('category_slug');
            $category->category_image_path = $request->input('category_image_path') ?? null;
            $category->category_image_alt = $request->input('category_image_alt') ?? null;
            $category->category_status = $request->input('category_status') ?? Category::CATEGORY_STATUS_INACTIVE;
            $category->parent_id = $request->input('parent_slug') ? $parent->category_id : null;
            $category->category_type = $request->input('category_type');
            $category->category_description = $request->input('category_description');
            $category->category_topbar_index = null;
            $category->category_home_index = null;
            $category->category_release = $request->input('category_release') ?: now();

            $category->save();
            DB::commit();
            return CreateCategoryDTO::fromModel($category);
        } catch (\Exception $e) {
            DB::rollBack();
            return 'Failed to create category: ' . $e->getMessage();
        }
    }

    /**
     * Deletes a category by setting its status to deleted.
     *
     * @param \Illuminate\Http\Request $request The HTTP request containing the category slug.
     * @return string A success message on successful deletion, or an error message on failure.
     */
    public function deleteCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_slug' => 'required|string',
        ]);

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        $category = Category::where('category_slug', $request->input('category_slug'))->first();
        if (!$category) {
            return 'Category not found';
        }

        foreach ($category->products as $product) {
            $canNoDelete = ProductService::checkProductSold($product, 'This category have products that was currently sold and cannot be deleted.');
            if ($canNoDelete != null) {
                return $canNoDelete;
            }
        }
        if ($category->childrens) {
            foreach ($category->childrens as $cate) {
                foreach ($cate->products as $product) {
                    $canNoDelete = ProductService::checkProductSold($product, 'This category has child categories containing products that are currently being sold and cannot be deleted.');
                    if ($canNoDelete != null) {
                        return $canNoDelete;
                    }
                }
            }
        }

        DB::beginTransaction();
        try {
            $category->category_status = Category::CATEGORY_STATUS_DELETE;
            $category->save();
            DB::commit();
            return 'Category deleted successfully';
        } catch (\Exception $e) {
            DB::rollBack();
            return 'Failed to delete category: ' . $e->getMessage();
        }
    }

    /**
     * Updates an existing category with new data.
     *
     * @param \Illuminate\Http\Request $request The HTTP request containing updated category data.
     * @return UpdateCategoryDTO|string The DTO representing the updated category on success, or an error message on failure.
     */
    public function updateCategory(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|string',
            'category_name' => 'required|string',
            'category_slug' => 'required|string',
            'category_image_path' => 'nullable|string',
            'category_image_alt' => 'nullable|string',
            'category_status' => 'nullable|numeric',
            'parent_slug' => 'nullable|string',
            'category_description' => 'nullable|string',
            'category_type' => 'required|numeric',
            'category_release' => 'nullable|date',
            'updated_at' => 'required|string'
        ]);

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        $category = Category::where('category_id', $request->input('category_id'))->first();
        if (!$category) {
            return 'Category not found';
        }

        $clientUpdatedAt = Carbon::parse($request->input('updated_at'));
        $actualUpdatedAt = $category->updated_at;

        if ($clientUpdatedAt->diffInMinutes($actualUpdatedAt) <= 3 && $clientUpdatedAt->lt($actualUpdatedAt)) {
            return 'Category are updating by another user';
        }

        if ($request->input('parent_slug')) {
            $parent = Category::where('category_slug', '=', $request->input('parent_slug'))->first();
            if (!$parent) {
                return 'Parent category not found';
            }
        }

        if ($request->input('category_slug')) {
            $slug = Category::where('category_slug', $request->input('category_slug'))
                ->where('category_id', '!=', $request->input('category_id'))
                ->first();
            if ($slug) {
                return 'Slug already exists';
            }
        }

        $checkTypeCategoryError = $this->checkTypeCategoryMustHaveImage($request);
        if ($checkTypeCategoryError) {
            return $checkTypeCategoryError;
        }

        if ($request->input('category_image_path') && !file_exists(base_path($request->input('category_image_path')))) {
            return 'Image path does not exist';
        }

        if ($request->input('category_release') && $request->input('category_release') < now()) {
            return 'Release date must be today or in the future';
        }

        DB::beginTransaction();
        try {
            $category->category_name = $request->input('category_name');
            $category->category_slug = $request->input('category_slug');
            if ($request->input('category_image_path') && $request->input('category_image_alt')) {
                $category->category_image_path = $request->input('category_image_path');
                $category->category_image_alt = $request->input('category_image_alt');
            }
            $category->category_status = $request->input('category_status') ? Category::CATEGORY_STATUS_ACTIVE : Category::CATEGORY_STATUS_INACTIVE;
            $category->parent_id = $request->input('parent_slug') ? $parent->category_id : null;
            $category->category_type = $request->input('category_type');
            $category->category_release = $request->input('category_release');
            $category->category_description = $request->input('category_description');
            $category->updated_at = $request->input('updated_at');
            $category->save();
            DB::commit();
            return UpdateCategoryDTO::fromModel($category);
        } catch (\Exception $e) {
            DB::rollBack();
            return 'Failed to update category: ' . $e->getMessage();
        }
    }

    /**
     * Retrieves a category by its slug.
     *
     * @param \Illuminate\Http\Request $request The HTTP request containing the category slug.
     * @param int $role The role of the requester (admin or user).
     * @return GetCategoryDTO|string A DTO representing the category if found, or an error message if not found.
     */
    public function getCategoryBySlug(Request $request, $role)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'category_slug' => 'required|string',
        ]);

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        $category = null;
        if ($role == CategoryService::ROLE_ADMIN) {
            $category = Category::where('category_slug', $request->input('category_slug'))
                ->where('category_status', '!=' , Category::CATEGORY_STATUS_DELETE)
                ->first();
            if (!$category) {
                return 'Category not found ';
            }
            if(self::allParentsAreActive($category) == false){
                return 'Category have parent not found ';
            }
            return GetCategoryAdminDTO::fromModel($category);
        } elseif ($role == CategoryService::ROLE_USER) {
            $category = Category::where('category_slug', $request->input('category_slug'))
                ->where('category_status', Category::CATEGORY_STATUS_ACTIVE)
                ->where('category_release', '<=', now())
                ->first();
            if (!$category) {
                return 'Category not found';
            }
        } else {
            return 'You do not have permission to view the category';
        }

        return GetCategoryDTO::fromModel($category);
    }

    /**
     * Retrieves a paginated list of categories based on the request parameters.
     *
     * @param \Illuminate\Http\Request $request The HTTP request containing pagination parameters.
     * @param int $role The role of the requester (admin or user).
     * @return PaginatedDTO|string A paginated list of category DTOs if found, or a message if no categories are found.
     */
    public function getListCategoryPerPage(Request $request, $role, $getAll = false)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'page' => 'nullable|integer',
            'per_page' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        $perPage = $request->input('per_page', CategoryService::PER_PAGE_DEFAULT);

        $page = $request->input('page', CategoryService::PAGE_SIZE_DEFAULT);

        $skip = ($page - 1) * $perPage;

        $categories = null;

        if ($role == CategoryService::ROLE_ADMIN) {
            $categories = Category::where('category_status', '!=',  Category::CATEGORY_STATUS_DELETE)->get();
            return GetCategoryAdminDTO::fromModels($categories, $getAll);
        } elseif ($role == CategoryService::ROLE_USER) {
            $categories = Category::where('category_status', Category::CATEGORY_STATUS_ACTIVE)
                ->where('category_release', '<=', now())
                ->get();
            if ($categories->isEmpty()) {
                return 'No categories found';
            }
        } else {
            return 'You do not have permission to view categories';
        }

        if ($categories->isEmpty()) {
            return 'No categories found';
        }
        return GetCategoryDTO::fromModels($categories, $getAll);
    }

    /**
     * Checks if a category of a certain type must have an image.
     *
     * @param \Illuminate\Http\Request $request The HTTP request containing the category data.
     * @return string|null An error message if the category type must have an image but does not, or null if the category type does not require an image.
     */
    public function checkTypeCategoryMustHaveImage(Request $request)
    {
        if ($request->input('category_type') == Category::CATEGORY_TYPE_BRAND  && !$request->input('category_image_path')) {
            return 'Brand categories must have an image';
        }
        return null;
    }

    /**
     * Recursively determines the level of a category in the hierarchy.
     *
     * @param \App\Models\Category $category The category for which to determine the level.
     * @param int $levelDTO The current level in the hierarchy (used for recursion).
     * @return int The level of the category in the hierarchy.
     */
    public static function getLevel(Category $category, int $levelDTO = 0): int
    {
        if ($category->parent_id) {
            $parent = $category->parent()->first();
            if ($parent) {
                $levelDTO = self::getLevel($parent, $levelDTO + 1);
            }
        }
        return $levelDTO;
    }
    /**
     * Recursively determines the number of products in a category and its children.
     *
     * @param \App\Models\Category $category The category for which to determine the product count.
     * @param int $productCount The current product count (used for recursion).
     * @param array $uniqueProductIds An array of unique product IDs (used for recursion).
     * @return int The number of products in the category and its children.
     */
    public static function getProductCount(Category $category, int $productCount = 0, array &$uniqueProductIds = []): int
    {
        $products = $category->products()->get();
        foreach ($products as $product) {
            if (!in_array($product->product_id, $uniqueProductIds) && $product->post()->first()->post_release <= now() && $product->post()->first()->post_status == Post::STATUS_RELEASE) {
                $uniqueProductIds[] = $product->product_id;
                $productCount++;
            }
        }

        $children = $category->childrens;
        if ($children) {
            foreach ($children as $child) {
                $productCount = self::getProductCount($child, $productCount, $uniqueProductIds);
            }
        }

        return $productCount;
    }

    public static function getChildrenCategoryCount(Category $category, int $count = 0): int
    {
        $children = $category->childrens;

        foreach ($children as $child) {
            $count++;
            $count = self::getChildrenCategoryCount($child, $count);
        }

        return $count;
    }


    public static function hasDisabledAncestor(Category $category): bool
    {
        $parent = $category->parent;

        while ($parent) {
            if ($parent->category_status === Category::CATEGORY_STATUS_DELETE) {
                return true;
            }
            $parent = $parent->parent;
        }

        return false;
    }

    public static function getMaxParentDepth(): int
    {
        $maxDepth = 0;

        $categories = Category::all();

        foreach ($categories as $category) {
            $depth = 0;
            $current = $category;

            while ($current->parent_id) {
                $depth++;
                $current = $current->parent;
            }

            if ($depth > $maxDepth) {
                $maxDepth = $depth;
            }
        }

        return $maxDepth;
    }

    public static function allParentsAreActive($category)
    {
        while ($category->parent) {
            $category = Category::find($category->parent->category_id);
            if ($category->category_status == Category::CATEGORY_STATUS_DELETE) {
                return false;
            }
        }
        return true;
    }
}
