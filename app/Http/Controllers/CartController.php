<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use App\Services\CategoryService;
use App\Services\CouponService;
use App\Services\OrderService;
use App\Services\QuestionService;
use App\Services\UserService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected $cartService;
    protected $orderService;
    protected $userService;
    protected $categoryService;
    protected $couponService;
    protected $questionService;

    public function __construct(CartService $cartService, OrderService $orderService, UserService $userService, CategoryService $categoryService, CouponService $couponService, QuestionService $questionService)
    {
        $this->cartService = $cartService;
        $this->orderService = $orderService;
        $this->userService = $userService;
        $this->categoryService = $categoryService;
        $this->couponService = $couponService;
        $this->questionService = $questionService;
    }

    public function addToCart(Request $request)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        if(parent::checkMaintenance($user) == "off"){
            return redirect()->route('maintenance');
        }
        if (!$this->userService->checkUserSecurity($request, $user->user_id, $this->questionService)) {
            return redirect()->route('home')->with([
                'need_security' => 'Security building steps need to be taken'
            ]);
        }
        if ($user) {
            if (parent::checkUserInPage($user->roles) == false) {
                return redirect()->back()->with('add_to_cart_failed', "You are not allowed to add to cart");
            } 
        }
        if ($user != null) {
            if (is_int($user)) {
                return redirect()->route('login')->with('token_expired', "Login session expired");
            }

            $cart = $this->cartService->createCart($user->user_id);
            if (parent::checkIsString($cart)) {
                return redirect()->back()->with('add_to_cart_failed', $cart);
            }

            $request->merge(['cart_id' => $cart->cart_id]);
            $add = $this->cartService->insertProductToCart($request, $user->user_id);
            if (parent::checkIsString($add)) {
                return redirect()->back()->with('add_to_cart_failed', $add);
            } else {
                return redirect()->back()->with('add_to_cart_success', 'Add to cart successfully');
            }
        } else {
            return redirect()->back()->with('add_to_cart_failed', "You need to log in");
        }
    }

    public function deleteItemOfCart(Request $request)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        if(parent::checkMaintenance($user) == "off"){
            return redirect()->route('maintenance');
        }
        if ($user) {
            if (parent::checkUserInPage($user->roles) == false) {
                return redirect()->back()->with('add_to_cart_failed', "You are not allowed to delete to cart");
            } 
        }
        if ($user != null) {
            if (is_int($user)) {
                return redirect()->route('login')->with('token_expired', "Login session expired");
            }

            $delete = $this->cartService->removeProductFromCart($request, $user->user_id);
            if (parent::checkIsString($delete)) {
                return redirect()->back()->with('delete_failed', $delete);
            } else {
                return redirect()->back()->with('delete_success', "Delete successfully");
            }
        } else {
            return redirect()->back()->with('add_to_cart_failed', "You need to log in");
        }
    }

    public function cart(Request $request)
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

        return view('cart.index', [
            "categories" => $categories,
            "position_logo" => parent::getPositionLogo($categories),
            "user" => $user,
            "user_name" => $user_name,
            "cart" => $cart
        ]);
    }
}
