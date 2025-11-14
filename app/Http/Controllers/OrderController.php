<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use App\Services\CategoryService;
use App\Services\OrderService;
use App\Services\OTPService;
use App\Services\QuestionService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class OrderController extends Controller
{
    protected $categoryService;
    protected $userService;
    protected $otpService;
    protected $cartService;
    protected $questionService;
    protected $orderService;

    public function __construct(OrderService $orderService,CategoryService $categoryService, UserService $userService, OTPService $otpService, CartService $cartService, QuestionService $questionService)
    {
        $this->userService = $userService;
        $this->categoryService = $categoryService;
        $this->otpService = $otpService;
        $this->cartService = $cartService;
        $this->questionService = $questionService;
        $this->orderService = $orderService;
    }

    public function order(Request $request){
        $user = parent::checkTokenWhenReload($request, $this->userService);
        if(parent::checkMaintenance($user) == "off"){
            return redirect()->route('maintenance');
        }
        if (!$this->userService->checkUserSecurity($request, $user->user_id, $this->questionService)) {
            return redirect()->route('home')->with([
                'need_security' => 'Security building steps need to be taken'
            ]);
        }
        $categories = $this->categoryService->getListCategoryPerPage($request, CategoryService::ROLE_USER);
        $cart = null;
        $user_name = "";
        $orders = null;
        
        if ($user != null) {
            $user_name = $user->user_last_name;
            $cart = $this->cartService->getCartsByUser($user->user_id);
            if (parent::checkIsString($cart)) {
                $cart = null;
            }
            $orders = $this->orderService->getOrdersByUser($request , $user->user_id);
            if(parent::checkIsString($orders)){
                $orders = null;
            }
        } else {
            if (is_int($user)) {
                return redirect()->route('login')->with('token_expired', "Login session expired");
            }
        }

        return view('user.order.index', [
            "categories" => $categories,
            "position_logo" => parent::getPositionLogo($categories),
            "user" => $user,
            "user_name" => $user_name,
            "cart" => $cart,
            "orders" => $orders
        ]);
    }

    public function getOrderByAdmin(Request $request){
        $user = parent::checkTokenWhenReload($request, $this->userService); 
        parent::checkAdminInPage($user);
        $orders = $this->orderService->getOrdersByAdmin($request, $this->userService);
        return response()->json($orders);
    }
}