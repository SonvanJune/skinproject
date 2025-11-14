<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use App\Services\CategoryService;
use App\Services\QuestionService;
use App\Services\SlideshowImageService;
use App\Services\UserService;
use App\Services\WishlistService;
use Illuminate\Http\Request;

class WishlistController extends Controller
{

    protected $categoryService;
    protected $userService;
    protected $cartService;
    protected $questionService;
    protected $slideshowService;
    protected $wishListService;

    public function __construct(CategoryService $categoryService, UserService $userService, CartService $cartService, QuestionService $questionService, SlideshowImageService $slideshowImageService, WishlistService $wishListService)
    {
        $this->categoryService = $categoryService;
        $this->userService = $userService;
        $this->cartService = $cartService;
        $this->questionService = $questionService;
        $this->slideshowService = $slideshowImageService;
        $this->wishListService = $wishListService;
    }

    public function wishlist(Request $request)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        if(parent::checkMaintenance($user) == "off"){
            return redirect()->route('maintenance');
        }
        $categories = $this->categoryService->getListCategoryPerPage($request, CategoryService::ROLE_USER);
        $cart = null;
        $user_name = "";
        $wistList = null;
        if ($user != null) {
            $user_name = $user->user_last_name;
            $cart = $this->cartService->getCartsByUser($user->user_id);
            if (parent::checkIsString($cart)) {
                $cart = null;
            }
            $wistList = $this->wishListService->readUserWishlist($request,$user);
            if (parent::checkIsString($wistList)) {
                $wistList = null;
            }
        } else {
            if (is_int($user)) {
                return redirect()->route('login')->with('token_expired', "Login session expired");
            }
        }

        return view('wishlist.index', [
            "categories" => $categories,
            "position_logo" => parent::getPositionLogo($categories),
            "user_name" => $user_name,
            "cart" => $cart,
            "wistList" => $wistList
        ]);
    }

    public function addToWishlist(Request $request)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService, true);
        if(parent::checkMaintenance($user) == "off"){
            return redirect()->route('maintenance');
        }
        $user = $this->userService->encryptUser($user);
        if ($user != null) {
            $addToWishList = $this->wishListService->insertProductToWishlist($request, $user);
            if (!parent::checkIsString($addToWishList)) {
                return redirect()->back()->with('success', $addToWishList->message);
            } else {
                return redirect()->back()->with('error', $addToWishList);
            }
        } else {
            if (is_int($user)) {
                return redirect()->route('login')->with('token_expired', "Login session expired");
            }
        }
    }
}
