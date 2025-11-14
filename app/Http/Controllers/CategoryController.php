<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Services\CartService;
use App\Services\CategoryService;
use App\Services\ProductService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

use function PHPUnit\Framework\isEmpty;

class CategoryController extends Controller
{
    protected $categoryService;
    protected $productService;
    protected $userService;
    protected $cartService;
    protected $productPerpage = 9;

    public function __construct(CategoryService $categoryService, ProductService $productService, UserService $userService, CartService $cartService)
    {
        $this->categoryService = $categoryService;
        $this->productService = $productService;
        $this->userService = $userService;
        $this->cartService = $cartService;
    }

    public function productCategory(Request $request, $slug)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        if(parent::checkMaintenance($user) == "off"){
            return redirect()->route('maintenance');
        }
        if($user){
            parent::checkUserInPage($user->roles);
        }
        $categories = $this->categoryService->getListCategoryPerPage($request, CategoryService::ROLE_USER);
        $cart = null;
        $user_name = "";
        if ($user != null) {
            $user_name = $user->user_last_name;
            $cart = $this->cartService->getCartsByUser($user->user_id);
            if (parent::checkIsString($cart)) {
                $cart = null;
            }
        } else {
            if (is_int($user)) {
                return redirect()->route('login')->with('token_expired', "Login session expired");
            } 
        }

        if (parent::checkIsString($categories)) {
            abort(404);
        }

        $page = 1;
        if (!is_null($request->query('page')) || $request->query('page') != 0) {
            $page = $request->query('page');
        }

        $slug_not_found = parent::checkCategorySlug($categories, $slug);
        if ($slug_not_found == false) {
            $request->merge(['category_slug' => $slug, 'per_page' => $this->productPerpage, 'page' => $page]);
            $category = $this->categoryService->getCategoryBySlug($request, CategoryService::ROLE_USER);
            $productDtos = $this->productService->getListProductByCategorySlugPerPage($request, CategoryService::ROLE_USER, $user);
            if (parent::checkIsString($productDtos) || parent::checkIsString($category)) {
                abort(404);
            }

            $products = new Collection($productDtos->data);

            if ($category->type == Category::CATEGORY_TYPE_BRAND) {
                return redirect()->route('brand', ['slug' => $category->slug]);
            } else {
                return view('product-category.product-list.index', [
                    "categories" => $categories,
                    "position_logo" => parent::getPositionLogo($categories),
                    "category_detail" => $category,
                    "list_product" => $products,
                    "total_product_per_page" => $productDtos->total,
                    "total_page" => $productDtos->last_page,
                    "current_page" => $productDtos->current_page,
                    "user_name" => $user_name,
                    "cart" => $cart
                ]);
            }
        } else {
            abort(404);
        }
    }
}