<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use App\Services\CategoryService;
use App\Services\FileService;
use App\Services\ProductService;
use App\Services\UserService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $categoryService;
    protected $productService;
    protected $userService;
    protected $cartService;

    public function __construct(CategoryService $categoryService, ProductService $productService, UserService $userService, CartService $cartService)
    {
        $this->categoryService = $categoryService;
        $this->productService = $productService;
        $this->userService = $userService;
        $this->cartService = $cartService;
    }

    public function product(Request $request, $slug)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        if(parent::checkMaintenance($user) == "off"){
            return redirect()->route('maintenance');
        }
        if ($user) {
            parent::checkUserInPage($user->roles);
        }
        $categories = $this->categoryService->getListCategoryPerPage($request, CategoryService::ROLE_USER);
        $cart = null;
        $user_name = "";
        $request->merge(['post_slug' => $slug]);
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

        $this->productService->updateProductViews($request);

        $product = $this->productService->getProductBySlug($request, CategoryService::ROLE_USER, $user);

        if (parent::checkIsString($categories) || parent::checkIsString($product)) {
            abort(404);
        }

        $slug_category = $product->category_slug;
        foreach ($slug_category as $slug) {
            $slug_not_found = parent::checkCategorySlug($categories, $slug);
        }

        if ($slug_not_found == false) {
            $categorySlugs = [];
            foreach ($slug_category as $slug) {
                $request->merge(['category_slug' => $slug]);
                $category = $this->categoryService->getCategoryBySlug($request, CategoryService::ROLE_USER);
                $categorySlugs[] = $category;
            }
            return view('product-category.product-detail.index', [
                "categories" => $categories,
                "position_logo" => $this->getPositionLogo($categories),
                "categories_detail" => $categorySlugs,
                "product" => $product,
                "user_name" => $user_name,
                "cart" => $cart
            ]);
        } else {
            abort(404);
        }
    }

    public function downloadProduct(Request $request)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService, true);
        if(parent::checkMaintenance($user) == "off"){
            return redirect()->route('maintenance');
        }
        if ($user) {
            $download = $this->productService->downloadProduct($request, $user, $this->userService);
            if (parent::checkIsString($download)) {
                return redirect()->back()->with('error', $download);
            }
            return response($download->zip_content, $download->status, $download->headers);
        } else {
            abort(404);
        }
    }
}
