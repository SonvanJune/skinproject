<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use App\Services\CategoryService;
use App\Services\SearchService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SearchController extends Controller
{

    protected $categoryService;
    protected $userService;
    protected $cartService;
    protected $searchService;

    public function __construct(CategoryService $categoryService, UserService $userService, CartService $cartService, SearchService $searchService)
    {
        $this->categoryService = $categoryService;
        $this->userService = $userService;
        $this->cartService = $cartService;
        $this->searchService = $searchService;
    }

    public function index(Request $request)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        if(parent::checkMaintenance($user) == "off"){
            return redirect()->route('maintenance');
        }
        $categories = $this->categoryService->getListCategoryPerPage($request, CategoryService::ROLE_USER);
        $cart = null;
        $user_name = "";
        $searchResult = $this->searchService->search($request, $user);
        if (parent::checkIsString($searchResult)) {
            $searchResult = null;
        }
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
        return view('search.index', [
            "categories" => $categories,
            "position_logo" => parent::getPositionLogo($categories),
            "user_name" => $user_name,
            "cart" => $cart,
            "searchResult" => $searchResult,
            "searchQuery" => $request->query('s')
        ]);
    }

    public function search(Request $request)
    {
        $search = $this->searchService->search($request, null);
        return response()->json($search);
    }

    public function searchCategory(Request $request)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        if ($user != null) {
            $search = $this->searchService->searchCategory($request);
            return response()->json($search);
        }
    }

    public function searchProduct(Request $request)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        if ($user != null) {
            $search = $this->searchService->searchProduct($request);
            return response()->json($search);
        }
    }

    public function searchPost(Request $request)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        if ($user != null) {
            $search = $this->searchService->searchPost($request, $this->userService);
            return response()->json($search);
        }
    }

    public function searchCoupon(Request $request)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        if ($user != null) {
            $search = $this->searchService->searchCoupon($request);
            return response()->json($search);
        }
    }
}
