<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use App\Services\CategoryService;
use App\Services\MailService;
use App\Services\ProductService;
use App\Services\QuestionService;
use App\Services\Service;
use App\Services\SlideshowImageService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;


class PageController extends Controller
{

    protected $categoryService;
    protected $userService;
    protected $cartService;
    protected $questionService;
    protected $slideshowService;
    protected $mailService;
    protected $service;
    protected $productService;

    public function __construct(CategoryService $categoryService, UserService $userService, CartService $cartService, QuestionService $questionService, SlideshowImageService $slideshowImageService, MailService $mailService, Service $service, ProductService $productService)
    {
        $this->categoryService = $categoryService;
        $this->userService = $userService;
        $this->cartService = $cartService;
        $this->questionService = $questionService;
        $this->slideshowService = $slideshowImageService;
        $this->mailService = $mailService;
        $this->service = $service;
        $this->productService = $productService;
    }

    public function home(Request $request)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        if (parent::checkMaintenance($user) == "off") {
            return redirect()->route('maintenance');
        }
        $categories = $this->categoryService->getListCategoryPerPage($request, CategoryService::ROLE_USER);
        $cart = null;
        $questions = $this->questionService->getQuestionList($request);
        $user_name = "";
        $productNews = $this->productService->getListProductNew($request, $user);
        $productPopulars = $this->productService->getListProductPopular($request, $user);
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

        if ($user_name == -2) {
            return redirect()->route('login')->with('permission_denied', "Wrong account or password");
        }

        $slideImages = $this->slideshowService->getListSlideshowImage();
        if (parent::checkIsString($slideImages)) {
            $slideImages = null;
        }

        return view('home.index', [
            "categories" => $categories,
            "position_logo" => parent::getPositionLogo($categories),
            "user_name" => $user_name,
            "cart" => $cart,
            "securityQuestions" => $questions->data,
            "countQuestion" => $this->questionService::MAX_QUESTIONS_QUANTITY,
            "slideImages" => $slideImages,
            "productNews" => $productNews->data,
            "productPopulars" => $productPopulars->data
        ]);
    }

    public function contact(Request $request)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        if (parent::checkMaintenance($user) == "off") {
            return redirect()->route('maintenance');
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

        return view('contact.index', [
            "categories" => $categories,
            "position_logo" => parent::getPositionLogo($categories),
            "user_name" => $user_name,
            "cart" => $cart
        ]);
    }

    public function sendContactEmail(Request $request)
    {
        $sendEmail = $this->mailService->sendContactEmail($request);
        if (parent::checkIsString($sendEmail)) {
            return redirect()->route('contact')->with('error', $sendEmail);
        }
        return redirect()->route('contact')->with('success', "Send contact email successfully");
    }

    public function admin(Request $request)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        parent::checkAdminInPage($user);
        $total = $this->service->getTotal();
        return view('admin.index', ['total' => $total]);
    }

    public function adminLogin(Request $request)
    {
        $user_name = parent::checkTokenWhenReload($request, $this->userService);
        if ($user_name != null) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login.index');
    }

    public function newProducts(Request $request)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        if (parent::checkMaintenance($user) == "off") {
            return redirect()->route('maintenance');
        }
        $categories = $this->categoryService->getListCategoryPerPage($request, CategoryService::ROLE_USER);
        $cart = null;
        $user_name = "";
        $newProducts = $this->productService->getListProductNew($request, $user, true);
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

        return view('news.index', [
            "categories" => $categories,
            "position_logo" => parent::getPositionLogo($categories),
            "user_name" => $user_name,
            "cart" => $cart,
            "news" => $newProducts
        ]);
    }

    public function popularProducts(Request $request)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        if (parent::checkMaintenance($user) == "off") {
            return redirect()->route('maintenance');
        }
        $categories = $this->categoryService->getListCategoryPerPage($request, CategoryService::ROLE_USER);
        $cart = null;
        $user_name = "";
        $popularsProducts = $this->productService->getListProductPopular($request, $user, true);
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

        return view('popular.index', [
            "categories" => $categories,
            "position_logo" => parent::getPositionLogo($categories),
            "user_name" => $user_name,
            "cart" => $cart,
            "populars" => $popularsProducts
        ]);
    }

    public function saleProducts(Request $request)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        if (parent::checkMaintenance($user) == "off") {
            return redirect()->route('maintenance');
        }
        $categories = $this->categoryService->getListCategoryPerPage($request, CategoryService::ROLE_USER);
        $cart = null;
        $user_name = "";
        $saleProducts = $this->productService->getListProductSale($request, $user);
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

        return view('sale.index', [
            "categories" => $categories,
            "position_logo" => parent::getPositionLogo($categories),
            "user_name" => $user_name,
            "cart" => $cart,
            "sales" => $saleProducts
        ]);
    }

    public function maintenance(Request $request)
    {
        if (parent::checkMaintenance() == "on") {
            return redirect()->route('home');
        }
        return view('maintenance.index');
    }

    public function helps(Request $request)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        if (parent::checkMaintenance($user) == "off") {
            return redirect()->route('maintenance');
        }
        
        $categories = $this->categoryService->getListCategoryPerPage($request, CategoryService::ROLE_USER);
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

        return view('helps.index', [
            "categories" => $categories,
            "position_logo" => parent::getPositionLogo($categories),
            "user_name" => $user_name
        ]);
    }

    public function policies(Request $request)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        if (parent::checkMaintenance($user) == "off") {
            return redirect()->route('maintenance');
        }
        
        $categories = $this->categoryService->getListCategoryPerPage($request, CategoryService::ROLE_USER);
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

        return view('policy.index', [
            "categories" => $categories,
            "position_logo" => parent::getPositionLogo($categories),
            "user_name" => $user_name
        ]);
    }

    public function setLocale($locale)
    {
        App::setLocale($locale);

        $previous = url()->previous();
        $parsed = parse_url($previous);
        $path = $parsed['path'] ?? '/';

        $path = preg_replace('#^/(en|vi|ja|es|zh|fr)#', '', $path);

        return redirect("/$locale" . $path);
    }
}
