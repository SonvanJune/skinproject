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
        $isAjax = $request->ajax() || $request->wantsJson();

        if (parent::checkMaintenance($user) == "off") {
            if ($isAjax) {
                return response()->json(['status' => 'error', 'message' => 'Site in maintenance'], 503);
            }
            return redirect()->route('maintenance');
        }

        if (!$this->userService->checkUserSecurity($request, $user->user_id ?? null, $this->questionService)) {
            if ($isAjax) {
                return response()->json([
                    'status' => 'need_security',
                    'message' => 'Security building steps need to be taken'
                ], 403);
            }
            return redirect()->route('home')->with([
                'need_security' => 'Security building steps need to be taken'
            ]);
        }

        if ($user) {
            if (parent::checkUserInPage($user->roles) == false) {
                if ($isAjax) {
                    return response()->json(['status' => 'error', 'message' => "You are not allowed to add to cart"], 403);
                }
                return redirect()->back()->with('add_to_cart_failed', "You are not allowed to add to cart");
            } 
        }

        if ($user != null) {
            if (is_int($user)) {
                if ($isAjax) {
                    return response()->json(['status' => 'unauthenticated', 'message' => __('message.sessionExpired'), 'redirect' => route('login')], 401);
                }
                return redirect()->route('login')->with('token_expired', __('message.sessionExpired'));
            }

            $cart = $this->cartService->createCart($user->user_id);
            if (parent::checkIsString($cart)) {
                if ($isAjax) {
                    return response()->json(['status' => 'error', 'message' => $cart], 400);
                }
                return redirect()->back()->with('add_to_cart_failed', $cart);
            }

            $request->merge(['cart_id' => $cart->cart_id]);
            $add = $this->cartService->insertProductToCart($request, $user->user_id);
            if (parent::checkIsString($add)) {
                if ($isAjax) {
                    return response()->json(['status' => 'error', 'message' => $add], 400);
                }
                return redirect()->back()->with('add_to_cart_failed', $add);
            } else {
                if ($isAjax) {
                    $updatedCart = $this->cartService->getCartsByUser($user->user_id);
                    $cart = (!parent::checkIsString($updatedCart)) ? $updatedCart : null;
                    $cartCount = ($cart && isset($cart->products)) ? count($cart->products) : 1;
                    $categories = $this->categoryService->getListCategoryPerPage($request, CategoryService::ROLE_USER);
                    $cartHtml = view('component.cart-modal.cart-items', [
                        'cart' => $cart,
                        'categories' => $categories
                    ])->render();

                    return response()->json([
                        'status' => 'success',
                        'message' => __('message.addToCartSuccess'),
                        'cart_count' => $cartCount,
                        'cart_html' => $cartHtml
                    ]);
                }
                return redirect()->back()->with('add_to_cart_success', __('message.addToCartSuccess'));
            }
        } else {
            if ($isAjax) {
                return response()->json(['status' => 'unauthenticated', 'message' => __('message.pleaseLoginCart'), 'redirect' => route('login')], 401);
            }
            return redirect()->back()->with('add_to_cart_failed', __('message.pleaseLoginCart'));
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
                return redirect()->route('login')->with('token_expired', __('message.sessionExpired'));
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
                return redirect()->route('login')->with('token_expired', __('message.sessionExpired'));
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

    public function cartModalItems(Request $request)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        $cart = null;
        $cartCount = 0;
        if ($user && !is_int($user)) {
            $updatedCart = $this->cartService->getCartsByUser($user->user_id);
            if (!parent::checkIsString($updatedCart)) {
                $cart = $updatedCart;
                $cartCount = isset($cart->products) ? count($cart->products) : 0;
            }
        }
        $categories = $this->categoryService->getListCategoryPerPage($request, CategoryService::ROLE_USER);
        $cartHtml = view('component.cart-modal.cart-items', [
            'cart' => $cart,
            'categories' => $categories
        ])->render();

        return response()->json([
            'status' => 'success',
            'cart_count' => $cartCount,
            'cart_html' => $cartHtml
        ]);
    }
}
