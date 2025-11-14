<?php

namespace App\Services;

use App\DTOs\CreateProductDTO;
use App\DTOs\DeleteProductDTO;
use App\DTOs\DownloadProductDTO;
use App\DTOs\GetProductAdminDTO;
use App\DTOs\GetProductDTO;
use App\DTOs\GetUserDTO;
use App\DTOs\PaginatedDTO;
use App\DTOs\UpdateProductDTO;
use App\DTOs\UpdateViewProductDTO;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Post;
use App\Models\Product;
use App\Models\User;
use App\Models\ProductImage;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProductService
{
    // Constants for product-related operations
    public const PRODUCT_PRICE_MIN = 0;
    public const PRODUCT_VIEWS_MIN = 0;
    public const PRODUCT_FAKE_VIEWS_MIN = 0;
    public const PRODUCT_STATUS_VIEWS_DEFAULT = 0;
    public const PRODUCT_STATUS_FAKE_VIEWS_DEFAULT = 0;
    public const PRODUCT_SUBDAY = 2;
    public const ROLE_ADMIN = 0;
    public const ROLE_USER = 1;
    public const PAGE_SIZE_DEFAULT = 1;
    public const PER_PAGE_DEFAULT = 15;
    public const PAGE_SIZE_NEW_PRODUCT_DEFAULT = 1;
    public const PER_PAGE_NEW_PRODUCT_DEFAULT = 4;
    protected  $productImageService;
    protected $postService;
    protected $couponService;

    /**
     * ProductService constructor.
     *
     * @param ProductImageService $productImageService The service for product images
     * @param PostService $postService The service for posts
     * @param CouponService $couponService The service for coupons
     */
    public function __construct(ProductImageService $productImageService, PostService $postService, CouponService $couponService)
    {
        $this->productImageService = $productImageService;
        $this->postService = $postService;
        $this->couponService = $couponService;
    }

    /**
     * Create a new product
     *
     * @param Request $request The request containing product data
     * @return CreateProductDTO|string DTO of created product or error message
     */
    public function createProduct(Request $request, string $user_id)
    {
        if (!$user_id) {
            return 'User does not exist';
        }
        $validator = Validator::make(
            $request->all(),
            [
                'product_name' => 'required|string|max:255',
                'product_price' => 'required|numeric',
                'product_file_path' => 'required|string|max:255',
                'product_fake_views' => 'integer|nullable',
                'product_status_views' => 'integer|nullable',
                'post_slug' => 'required|string|max:255',
                'post_content' => 'required|string',
                'post_image_path' => 'required|string',
                'post_image_alt' => 'required|string',
                'categories' => 'required|array',
                'productImages' => 'nullable|array',
                'coupon_release' => 'nullable|date',
                'coupon_expired' => 'nullable|date',
                'coupon_per_hundred' => 'nullable|numeric',
                'coupon_price' => 'nullable|numeric',
                'product_status' => 'nullable|string',
                'product_release' => 'nullable|date',
            ]
        );
        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        $validateProduct = self::validateProduct($request);
        if ($validateProduct) {
            return $validateProduct;
        }

        $post_id = $this->postService->createProductDescription($request, $user_id);
        if (!$post_id) {
            return 'Failed to create product description';
        }

        DB::beginTransaction();
        try {
            $product = new Product();
            $product->product_id = (string) Str::uuid();
            $product->product_name = $request->input('product_name');
            $product->product_price = $request->input('product_price');
            $product->product_file_path = $request->input('product_file_path');
            $product->post_id = $post_id;
            $product->product_views = $request->input('product_views') ?? ProductService::PRODUCT_VIEWS_MIN;
            $product->product_fake_views = $request->input('product_fake_views') ?? ProductService::PRODUCT_FAKE_VIEWS_MIN;
            $product->product_status_views = $request->input('product_status_views') ?? ProductService::PRODUCT_STATUS_VIEWS_DEFAULT;
            $product->save();
            if ($request->input('categories')) {
                $product->categories()->attach($request->input('categories'));
            }

            if (!$product->product_id) {
                return 'Failed to create product';
            }

            if ($request->input('coupon_price') || $request->input('coupon_per_hundred')) {
                $request->merge([
                    'product_id' => $product->product_id,
                    'coupon_name' => $request->input('product_name') . '-' . $request->input('coupon_release')
                ]);
                $productCoupon = $this->couponService->createCoupon($request);
                if (!is_object($productCoupon)) {
                    return $productCoupon;
                }
            }

            $productImages = $request->input('productImages');
            foreach ($productImages as $productImage) {
                $productImage = $this->productImageService->createProductImageForProduct((array) $productImage, $product->product_id);
                if (!is_object($productImage)) {
                    return 'Failed to create product image';
                }
            }
            DB::commit();
            return CreateProductDTO::fromModel($product);
        } catch (\Exception $e) {
            DB::rollBack();
            return 'Failed to create product: ' . $e->getMessage();
        }
    }
    /**
     * Update an existing product
     *
     * @param Request $request The request containing updated product data
     * @return UpdateProductDTO|string DTO of updated product or error message
     */
    public function updateProduct(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'product_name' => 'required|string|max:255',
                'product_price' => 'required|numeric',
                'product_file_path' => 'required|string|max:255',
                'product_fake_views' => 'integer|nullable',
                'product_status_views' => 'integer|nullable',
                'post_id' => 'required|string|max:255',
                'post_slug' => 'required|string|max:255',
                'post_content' => 'required|string',
                'post_image_path' => 'required|string',
                'post_image_alt' => 'required|string',
                'categories' => 'required|array',
                'productImages' => 'nullable|array',
                'coupon_id' => 'nullable|string',
                'coupon_release' => 'nullable|date',
                'coupon_expired' => 'nullable|date',
                'coupon_per_hundred' => 'nullable|numeric',
                'coupon_price' => 'nullable|numeric',
                'product_status' => 'nullable|string',
                'product_release' => 'nullable|date',
                'updated_at' => 'required|date'
            ]
        );

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        $validateProduct = self::validateProduct($request);
        if ($validateProduct) {
            return $validateProduct;
        }

        $product = Product::where('post_id', $request->input('post_id'))->first();
        if (!$product) {
            return 'Product does not exist';
        }

        $clientUpdatedAt = Carbon::parse($request->input('updated_at'));
        $actualUpdatedAt = $product->updated_at;

        if ($clientUpdatedAt->diffInMinutes($actualUpdatedAt) <= 3 && $clientUpdatedAt->lt($actualUpdatedAt)) {
            return 'Product are updating by another user';
        }

        $post = $this->postService->updateProductDescription($request);
        if (!is_object($post)) {
            return 'Failed to update post of description!';
        }

        DB::beginTransaction();
        try {
            $product->product_name = $request->input('product_name');
            $product->product_price = $request->input('product_price');
            $product->product_file_path = $request->input('product_file_path');
            $product->product_views = $request->input('product_views') ?? ProductService::PRODUCT_VIEWS_MIN;
            $product->product_fake_views = $request->input('product_fake_views') ?? ProductService::PRODUCT_FAKE_VIEWS_MIN;
            $product->product_status_views = $request->input('product_status_views') ?? ProductService::PRODUCT_STATUS_VIEWS_DEFAULT;
            $product->updated_at = $request->input('updated_at');
            $product->save();

            if ($request->input('categories')) {
                $product->categories()->sync($request->input('categories'));
            }

            if (!$product->product_id) {
                return 'Failed to create product';
            }

            if ($request->input('coupon_id')) {
                $request->merge([
                    'coupon_id' => $request->input('coupon_id'),
                    'product_id' => $product->product_id
                ]);
                if (!$request->input('coupon_release') && !$request->input('coupon_expired')) {
                    //cancel coupon
                    $request->merge([
                        'coupon_expired' => $request->input('updated_at')
                    ]);
                    $productCoupon = $this->couponService->updateCoupon($request);
                    if (!is_object($productCoupon)) {
                        return $productCoupon;
                    }
                } else {
                    //update coupon
                    $request->merge([
                        'coupon_price' => $request->input('coupon_price') ?? null,
                        'coupon_per_hundred' => $request->input('coupon_per_hundred') ?? null,
                    ]);
                    $coupon = Coupon::find($request->input('coupon_id'));
                    if ($coupon->coupon_price != $request->input('coupon_price') || $coupon->coupon_per_hundred != $request->input('coupon_per_hundred')) {
                        $productCoupon = $this->couponService->updateCoupon($request);
                        if (!is_object($productCoupon)) {
                            return $productCoupon;
                        }
                    }
                }
            } else {
                if ($request->input('coupon_price') || $request->input('coupon_per_hundred')) {
                    $request->merge([
                        'product_id' => $product->product_id,
                        'coupon_name' => $request->input('product_name') . '-' . $request->input('coupon_release')
                    ]);
                    $productCoupon = $this->couponService->createCoupon($request);
                    if (!is_object($productCoupon)) {
                        return $productCoupon;
                    }
                }
            }

            ProductImage::where('product_id', $product->product_id)->delete();
            $productImages = $request->input('productImages');
            foreach ($productImages as $productImage) {
                $productImage = $this->productImageService->createProductImageForProduct((array) $productImage, $product->product_id);
                if (!is_object($productImage)) {
                    return $productImage;
                }
            }

            DB::commit();

            return UpdateProductDTO::fromModel("Product updated successfully!");
        } catch (\Exception $e) {
            DB::rollBack();
            return 'Failed to update product: ' . $e->getMessage();
        }
    }
    /**
     * Delete a product (soft delete by updating associated post)
     *
     * @param Request $request The request containing product slug
     * @return DeleteProductDTO|string Success or error message
     */
    public function deleteProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'post_slug' => 'required|string',
        ]);
        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        $post = Post::where('post_slug', $request->input('post_slug'))->first();
        if (!$post) {
            return 'Post does not exist';
        }

        $carts = Cart::where('cart_status', CartService::STATUS_BOUGHT)->get();
        foreach ($carts as $cart) {
            foreach ($cart->products as $product) {
                if ($product->product_id == $post->product->product_id) {
                    return 'This product is currently sold and cannot be deleted.';
                }
            }
        }

        $canNotDelete = self::checkProductSold($post->product, 'This product is currently sold and cannot be deleted.');
        if ($canNotDelete != null) {
            return $canNotDelete;
        }

        DB::beginTransaction();
        try {
            $post->post_status = Post::TYPE_POST_DELETE;
            $post->save();
            DB::commit();
            return DeleteProductDTO::fromModel('Product deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return 'Failed to delete product: ' . $e->getMessage();
        }
    }
    /**
     * Get a product by its slug
     *
     * @param Request $request The request containing product slug
     * @param int $role The role of the user (admin or regular user)
     * @return GetProductDTO|string DTO of the product or error message
     */
    public function getProductBySlug(Request $request, $role, ?GetUserDTO $user = null)
    {
        $validator = Validator::make($request->all(), [
            'post_slug' => 'required|string',
        ]);
        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }
        $product = null;
        if ($role == self::ROLE_ADMIN) {
            $product = Product::whereHas('post', function ($query) use ($request) {
                $query->where('post_slug', $request->input('post_slug'))->where('post_status', '!=', Post::TYPE_POST_DELETE);
            })->first();
            if (!$product) {
                return 'Product does not exist';
            }
            return GetProductAdminDTO::fromModel($product);
        }
        if ($role == self::ROLE_USER) {
            $product = Product::whereHas('post', function ($query) use ($request) {
                $query->where('post_slug', $request->input('post_slug'))
                    ->where('post_release', '<=', now())
                    ->where('post_status', Post::STATUS_RELEASE);
            })->first();
            if (!$product) {
                return 'Product does not exist';
            }
        }
        return GetProductDTO::fromModel($product, $user);
    }

    /**
     * Get a paginated list of products
     *
     * @param Request $request The request containing pagination parameters
     * @param int $role The role of the user (admin or regular user)
     * @return PaginatedDTO Paginated list of products
     */
    public function getListProductPerPage(Request $request, $role, ?GetUserDTO $user = null)
    {
        $validator = Validator::make($request->all(), [
            'page' => 'nullable|integer',
            'per_page' => 'nullable|integer',
            'key' => 'nullable|string',
        ]);
        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }
        $page = $request->input('page') ?? self::PAGE_SIZE_DEFAULT;
        $per_page = $request->input('per_page') ?? self::PER_PAGE_DEFAULT;
        $skip = ($page - 1) * $per_page;
        $key = $request->input('key', '') ?? "";

        $products = null;
        $productResponse = null;

        $products = Product::where('product_name', 'LIKE', '%' . $key . '%');

        if ($role == self::ROLE_ADMIN) {
            $products = $products->whereHas('post', function ($query) {
                $query->where('post_status', '!=', Post::TYPE_POST_DELETE);
            })->get();

            $productResponse = $products->skip($skip)->take($per_page);
            $total = $products->count();

            return PaginatedDTO::fromData(GetProductAdminDTO::fromModels($productResponse), $page, $per_page, $total, $key ?? "");
        } else if ($role == self::ROLE_USER) {
            $products = Product::where('product_name', 'LIKE', '%' . $key . '%')
                ->orWhere('product_file_path', 'LIKE', '%' . $key . '%')
                ->orWhereHas('post', function ($query) use ($key) {
                    $query->where('post_slug', 'LIKE', '%' . $key . '%');
                });

            $products = Product::whereHas('post', function ($query) use ($key) {
                $query->where('post_release', '<=', now())
                    ->where('post_status', Post::STATUS_RELEASE);
            })->get();

            $productResponse = $products->skip($skip)->take($per_page);
            $total = $products->count();

            return PaginatedDTO::fromData(GetProductDTO::fromModels($productResponse, $user), $page, $per_page, $total, $key ?? "");
        }
    }

    /**
     * Get a paginated list of products
     *
     * @param Request $request The request containing pagination parameters
     * @param int $role The role of the user (admin or regular user)
     * @return PaginatedDTO Paginated list of products
     */
    public function getListProductNew(Request $request, ?GetUserDTO $user = null, ?bool $isPage = false)
    {
        if ($isPage == false) {
            $page = self::PAGE_SIZE_NEW_PRODUCT_DEFAULT;
            $per_page = self::PER_PAGE_NEW_PRODUCT_DEFAULT;
        } else {
            $validator = Validator::make(
                $request->all(),
                [
                    'page' => 'nullable|numeric|integer',
                    'per_page' => 'nullable|numeric|integer'
                ]
            );

            if ($validator->fails()) {
                return implode("\n", $validator->errors()->all());
            }

            $per_page = $request->input('per_page', $this::PER_PAGE_DEFAULT);
            $page = $request->input('page', $this::PAGE_SIZE_DEFAULT);
        }
        $skip = ($page - 1) * $per_page;
        $products = null;
        $productResponse = null;
        $products = Product::whereHas('post', function ($query) {
            $query->where('post_release', '<=', now())
                ->where('post_status', Post::STATUS_RELEASE)->whereMonth('post_release', Carbon::now()->month)
                ->whereYear('post_release', Carbon::now()->year);
        })->get();
        $productResponse = $products->skip($skip)->take($per_page);
        $total = $products->count();
        return PaginatedDTO::fromData(GetProductDTO::fromModels($productResponse, $user), $page, $per_page, $total);
    }

    /**
     * Get a paginated list of products
     *
     * @param Request $request The request containing pagination parameters
     * @param int $role The role of the user (admin or regular user)
     * @return PaginatedDTO Paginated list of products
     */
    public function getListProductPopular(Request $request, ?GetUserDTO $user = null, ?bool $isPage = false)
    {
        if ($isPage == false) {
            $page = self::PAGE_SIZE_NEW_PRODUCT_DEFAULT;
            $per_page = self::PER_PAGE_NEW_PRODUCT_DEFAULT;
        } else {
            $validator = Validator::make(
                $request->all(),
                [
                    'page' => 'nullable|numeric|integer',
                    'per_page' => 'nullable|numeric|integer'
                ]
            );

            if ($validator->fails()) {
                return implode("\n", $validator->errors()->all());
            }

            $per_page = $request->input('per_page', $this::PER_PAGE_DEFAULT);
            $page = $request->input('page', $this::PAGE_SIZE_DEFAULT);
        }
        $skip = ($page - 1) * $per_page;
        $products = null;
        $productResponse = null;
        $products = Product::whereHas('post', function ($query) {
            $query->where('post_release', '<=', now())
                ->where('post_status', Post::STATUS_RELEASE);
        })->where('product_status_views', self::PRODUCT_STATUS_FAKE_VIEWS_DEFAULT)->get();
        $productResponse = $products->skip($skip)->take($per_page);
        $total = $products->count();
        return PaginatedDTO::fromData(GetProductDTO::fromModels($productResponse, $user), $page, $per_page, $total);
    }

    /**
     * Get a paginated list of products sales
     *
     * @param Request $request The request containing pagination parameters
     * @param int $role The role of the user (admin or regular user)
     * @return PaginatedDTO Paginated list of products
     */
    public function getListProductSale(Request $request, ?GetUserDTO $user = null)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'page' => 'nullable|numeric|integer',
                'per_page' => 'nullable|numeric|integer'
            ]
        );

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        $per_page = $request->input('per_page', $this::PER_PAGE_DEFAULT);
        $page = $request->input('page', $this::PAGE_SIZE_DEFAULT);

        $skip = ($page - 1) * $per_page;
        $products = null;
        $productResponse = null;
        $products = Product::whereHas('post', function ($query) {
            $query->where('post_release', '<=', now())
                ->where('post_status', Post::STATUS_RELEASE);
        })
            ->whereHas('coupons', function ($query) {
                $query->whereNull('coupon_code')
                    ->where('coupon_release', '<=', now())
                    ->where('coupon_expired', '>', now());
            })
            ->get();
        $productResponse = $products->skip($skip)->take($per_page);
        $total = $products->count();
        return PaginatedDTO::fromData(GetProductDTO::fromModels($productResponse, $user), $page, $per_page, $total);
    }

    /**
     * Get a paginated list of products by category
     *
     * @param Request $request The request containing pagination and category parameters
     * @param int $role The role of the user (admin or regular user)
     * @return PaginatedDTO Paginated list of products in the specified category
     */
    public function getListProductByCategorySlugPerPage(Request $request, $role, ?GetUserDTO $user)
    {
        $validator = Validator::make($request->all(), [
            'page' => 'nullable|integer',
            'per_page' => 'nullable|integer',
            'category_slug' => 'required|string',
        ]);

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        $page = $request->input('page') ?? self::PAGE_SIZE_DEFAULT;
        $perPage = $request->input('per_page') ?? self::PER_PAGE_DEFAULT;
        $skip = ($page - 1) * $perPage;

        $category = Category::where('category_slug', $request->input('category_slug'))->first();
        if (!$category) {
            return 'Category does not exist';
        }

        $allCategoryIds = $this->getAllCategoryIds($category);

        $productsQuery = Product::with(['categories', 'post'])
            ->whereHas('categories', function ($query) use ($allCategoryIds) {
                $query->whereIn('categories.category_id', $allCategoryIds);
            });

        if ($role == self::ROLE_USER) {
            $productsQuery->whereHas('post', function ($query) {
                $query->where('post_release', '<=', now())
                    ->where('post_status', Post::STATUS_RELEASE);
            });
        }

        $total = $productsQuery->count();
        $products = $productsQuery->skip($skip)->take($perPage)->get();


        return PaginatedDTO::fromData(GetProductDTO::fromModels($products, $user), $page, $perPage, $total);
    }

    /**
     * Download a product
     *
     * @param Request $request
     * @param String $user_id
     * @param UserService $userService
     * @return 
     */
    public function downloadProduct($request, User $user, UserService $userService)
    {
        $validator = Validator::make($request->all(), [
            'src_product' => 'required|string',
            'zip_file_name' => 'required|string',
        ]);
        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        $sourceFolderPath = $request->input('src_product');
        $password = $userService->decrypt_with_key($user->user_password_level_2, UserService::DEFAULT_ENCRYPT_KEY);
        $outputZipFilename = __('message.webName') . '_' . $request->input('zip_file_name') . '.zip';

        $zipDto = (new FileService())->createPasswordProtectedZipInMemory($sourceFolderPath, $password);
        if (is_string($zipDto)) {
            return "Unable to create zip file.";
        }

        $headers = [
            'Content-Type'        => 'application/zip',
            'Content-Disposition' => 'attachment; filename="' . $outputZipFilename . '"',
            'Content-Length'      => strlen($zipDto->content)
        ];

        return DownloadProductDTO::create($zipDto->content, 200, $headers);
    }

    /**
     * Recursively retrieves all category IDs, including children, for a given category.
     *
     * @param Category $category The category to retrieve IDs for.
     * @return array The array of category IDs.
     */
    private function getAllCategoryIds(Category $category): array
    {
        $categoryIds = [$category->category_id];
        foreach ($category->childrens as $childCategory) {
            $categoryIds = array_merge($categoryIds, $this->getAllCategoryIds($childCategory));
        }
        return $categoryIds;
    }


    /**
     * Recursively retrieves all child categories for a given parent category ID.
     *
     * @param string $parentId The ID of the parent category.
     * @return \Illuminate\Support\Collection The collection of child categories.
     */
    private function getChildCategories(string $parentId): \Illuminate\Support\Collection
    {
        $categories = Category::where('parent_id', $parentId)->get();

        foreach ($categories as $category) {
            $categories = $categories->merge($this->getChildCategories($category->category_id));
        }

        return $categories;
    }

    /**
     * Update the status and release date of a product
     *
     * @param Request $request The request containing product slug, status, and release date
     * @return UpdateProductDTO|string DTO of updated product or error message
     */
    public function updateStatusAndReleaseProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'post_slug' => 'required|string',
            'post_status' => 'boolean',
            'post_release' => 'date',
        ]);
        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        $postReleaseDate = $request->input('post_release');
        if ($postReleaseDate < now()) {
            return 'Release date is invalid!';
        }

        $post_slug = $request->input('post_slug');
        $product = Product::whereHas('post', function ($query) use ($post_slug) {
            $query->where('post_slug', $post_slug);
        })->first();
        if (!$product) {
            return 'Product does not exist';
        }
        DB::beginTransaction();
        try {
            $product->post->post_status = $request->input('post_status');
            $product->post->post_release = $request->input('post_release');
            $product->save();
            DB::commit();
            return UpdateProductDTO::fromModel($product);
        } catch (\Exception $e) {
            DB::rollBack();
            return 'Failed to update product: ' . $e->getMessage();
        }
    }
    /**
     * Validate product data
     *
     * @param Request $request The request containing product data
     * @return string|null Error message or null if validation passes
     */
    public function validateProduct(Request $request)
    {
        if ($request->input('post_slug') && $request->input('post_id')) {
            $slug = Post::where('post_slug', $request->input('post_slug'))
                ->where('post_id', '!=', $request->input('post_id'))
                ->first();
            if ($slug) {
                return 'Slug already exists';
            }
        } else {
            if (Post::where('post_slug', $request->input('post_slug'))
                ->first()
            ) {
                return 'Slug already exists';
            }
        }


        if ($request->input('product_file_path')) {
            if (!file_exists(base_path($request->input('product_file_path')))) {
                return 'File path does not exist';
            }
        }

        if ($request->input('product_file_path')) {
            if (is_file(base_path($request->input('product_file_path')))) {
                return 'File path is not a folder';
            }
        }

        if ($request->input('post_release') && $request->input('post_release') < now()) {
            return 'Release date is invalid!';
        }
        return null;
    }

    /**
     * Update the file path for a product in the 'products' table.
     *
     * @param string $oldProductFilePath
     * @param string $newProductFilePath
     * @return int
     */
    public function updateFilePathInProduct($oldProductFilePath, $newProductFilePath)
    {
        $product = DB::table('products')
            ->where('product_file_path', $oldProductFilePath)
            ->update(['product_file_path' => $newProductFilePath]);

        return $product;
    }

    public function isUsedInProduct($filePath)
    {
        $product = DB::table('products')
            ->where('product_file_path', $filePath)
            ->get();
        if ($product != null && $product->isNotEmpty()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Update view a product in the 'products' table.
     *
     * @param Request $request The request containing product slug
     * @return UpdateViewProductDTO|string DTO of updated views product or error message
     */
    public function updateProductViews(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'post_slug' => 'required|string',
        ]);
        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }
        $product = null;
        $product = Product::whereHas('post', function ($query) use ($request) {
            $query->where('post_slug', $request->input('post_slug'))
                ->where('post_release', '<=', now())
                ->where('post_status', Post::STATUS_RELEASE);
        })->first();
        if (!$product) {
            return 'Product does not exist';
        }
        $productSlug = $product->post->post_slug;
        $sessionKey = 'product_' . $productSlug;;

        if (!Session::has($sessionKey) || now()->diffInHours(Session::get($sessionKey)) >= 24) {
            DB::beginTransaction();

            try {
                $product->increment('product_views');

                Session::put($sessionKey, now());;

                DB::commit();
                return UpdateViewProductDTO::fromModel("Updated Views Successfully");
            } catch (\Exception $e) {
                DB::rollBack();
                return 'Failed to update product views: ' . $e->getMessage();
            }
        }
    }

    public static function checkProductSold(Product $product, string $message)
    {
        $carts = Cart::where('cart_status', CartService::STATUS_BOUGHT)->get();
        foreach ($carts as $cart) {
            foreach ($cart->products as $product) {
                if ($product->product_id == $product->product_id) {
                    return $message;
                }
            }
        }
        return null;
    }
}
