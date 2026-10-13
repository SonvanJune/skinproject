<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use App\Services\CategoryService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class BrandController extends Controller
{
    protected $categoryService;
    protected $userService;
    protected $cartService;
    protected $productPerpage = 12;

    public function __construct(CategoryService $categoryService, UserService $userService, CartService $cartService)
    {
        $this->categoryService = $categoryService;
        $this->userService = $userService;
        $this->cartService = $cartService;
    }

    public function brand(Request $request, $slug)
    {
        if ($slug === 'car-pdf-interrior-template') {
            return redirect()->route('brand', ['slug' => 'car-pdf-interior-template']);
        }

        $user = parent::checkTokenWhenReload($request, $this->userService);
        if (parent::checkMaintenance($user) == "off") {
            return redirect()->route('maintenance');
        }
        if ($user) {
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
                return redirect()->route('login')->with('token_expired', __('message.sessionExpired'));
            }
        }

        $slug_not_found = parent::checkCategorySlug($categories, $slug);
        if ($slug_not_found == false) {
            $page = 1;
            if (!is_null($request->query('page')) && $request->query('page') != 0) {
                $page = (int) $request->query('page');
            }

            $request->merge(['category_slug' => $slug, 'per_page' => $this->productPerpage, 'page' => $page]);
            $category = $this->categoryService->getCategoryBySlug($request, CategoryService::ROLE_USER);
            $category_child = new Collection($category->children ?? []);
            $cate_description = null;
            if ($category->category_description != null) {
                $cate_description = explode(',', $category->category_description);
            }

            return view('brand.index', [
                "categories" => $categories,
                "position_logo" => parent::getPositionLogo($categories),
                "user_name" => $user_name,
                "category" => $category,
                "category_child" => $category_child,
                "description" => $cate_description,
                "cart" => $cart,
            ]);
        } else {
            abort(404);
        }
    }
}