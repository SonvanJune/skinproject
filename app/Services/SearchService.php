<?php

namespace App\Services;

use App\DTOs\GetCategoryAdminDTO;
use App\DTOs\GetCouponDTO;
use App\DTOs\GetProductAdminDTO;
use App\DTOs\GetUserDTO;
use App\DTOs\PostAdminPageDTO;
use App\DTOs\SearchDTO;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Post;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SearchService
{
    /**
     * Searches for products, categories, and brands based on the search string 's' from the query parameters.
     *
     * @param Request $request The incoming HTTP request containing the search parameter.
     * @return mixed Either the search results for products, categories, brands or an error message if no results are found.
     */
    public function search(Request $request, ?GetUserDTO $user)
    {
        $validator = Validator::make(
            $request->all(),
            [
                's' => 'string|required',
            ]
        );

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        $searchTerm = $request->query('s');

        $products = Product::where('product_name', 'like', $searchTerm . '%')
            ->whereHas('post', function ($query) {
                $query->where('post_status', '!=', Post::TYPE_POST_DELETE)
                ->where('post_type', Post::TYPE_PRODUCT);
            })
            ->get();

        $maxDepth = CategoryService::getMaxParentDepth();
        $relations = implode('.', array_fill(0, $maxDepth, 'parent'));

        $categories = Category::where('category_status', '!=', Category::CATEGORY_STATUS_DELETE)
            ->where('category_name', 'like', $searchTerm . '%')
            ->where('category_type', '=', Category::CATEGORY_TYPE_DEFAULT)
            ->with($relations)
            ->get()
            ->filter(function ($category) {
                return !CategoryService::hasDisabledAncestor($category);
            });
        $branhs = Category::where('category_status', '!=', Category::CATEGORY_STATUS_DELETE)
            ->where('category_name', 'like', $searchTerm . '%')
            ->where('category_type', '=', Category::CATEGORY_TYPE_BRAND)
            ->with($relations)
            ->get()
            ->filter(function ($category) {
                return !CategoryService::hasDisabledAncestor($category);
            });
        $post = Post::where('post_status', '!=', Post::TYPE_POST_DELETE)->where('post_type', Post::TYPE_POST)->where('post_name', 'like', $searchTerm . '%')->get();
        return SearchDTO::create($products, $categories, $branhs, $post, new UserService, $user);
    }

    /**
     * Searches for categories on the search string 's' from the query parameters.
     *
     * @param Request $request The incoming HTTP request containing the search parameter.
     * @return mixed Either the search results for categories or an error message if no results are found.
     */
    public function searchCategory(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                's' => 'string|required',
            ]
        );

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        $searchTerm = $request->query('s');
        $maxDepth = CategoryService::getMaxParentDepth();
        $relations = implode('.', array_fill(0, $maxDepth, 'parent'));
        $categories = Category::where('category_status', '!=', Category::CATEGORY_STATUS_DELETE)
            ->where('category_name', 'like', $searchTerm . '%')
            ->with($relations)
            ->get()
            ->filter(function ($category) {
                return !CategoryService::hasDisabledAncestor($category);
            });
        return GetCategoryAdminDTO::fromModels($categories, true);;
    }

    /**
     * Searches for products on the search string 's' from the query parameters.
     *
     * @param Request $request The incoming HTTP request containing the search parameter.
     * @return mixed Either the search results for products or an error message if no results are found.
     */
    public function searchProduct(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                's' => 'string|required',
            ]
        );

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        $searchTerm = $request->query('s');
        $products = Product::whereHas('post', function ($query) {
            $query->where('post_status', '!=', Post::TYPE_POST_DELETE);
        })->where('product_name', 'like', '%' . $searchTerm . '%')->get();
        return GetProductAdminDTO::fromModels($products);
    }

    /**
     * Searches for posts on the search string 's' from the query parameters.
     *
     * @param Request $request The incoming HTTP request containing the search parameter.
     * @return mixed Either the search results for posts or an error message if no results are found.
     */
    public function searchPost(Request $request, UserService $userService)
    {
        $validator = Validator::make(
            $request->all(),
            [
                's' => 'string|required',
            ]
        );

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        $searchTerm = $request->query('s');
        $posts = Post::where('post_status', '!=', Post::TYPE_POST_DELETE)->where('post_type', '!=', Post::TYPE_PRODUCT)->where('post_name', 'like', '%' . $searchTerm . '%')->get();
        return PostAdminPageDTO::fromListModels($posts, $userService);
    }

    /**
     * Searches for coupons on the search string 's' from the query parameters.
     *
     * @param Request $request The incoming HTTP request containing the search parameter.
     * @return mixed Either the search results for coupons or an error message if no results are found.
     */
    public function searchCoupon(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                's' => 'string|required',
            ]
        );

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        $searchTerm = $request->query('s');
        $coupons = Coupon::where('coupon_name', 'like', '%' . $searchTerm . '%')
            ->orWhere('coupon_code', 'like', '%' . $searchTerm . '%')
            ->orderByRaw('
            CASE 
            WHEN coupon_expired >= NOW() THEN 1
            ELSE 2
            END
            ')
            ->orderBy('coupon_release', 'desc')
            ->get();
        return GetCouponDTO::fromModels($coupons);
    }
}
