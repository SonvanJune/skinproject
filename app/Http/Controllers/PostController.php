<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use App\Services\CategoryService;
use App\Services\PostService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class PostController extends Controller
{
    protected $postService;
    protected $categoryService;
    protected $userService;
    protected $cartService;
    protected $postPerpage = 6;

    public function __construct(PostService $postService, CategoryService $categoryService, UserService $userService, CartService $cartService)
    {
        $this->postService = $postService;
        $this->categoryService = $categoryService;
        $this->userService = $userService;
        $this->cartService = $cartService;
    }

    public function blog(Request $request)
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
        $request->merge(['per_page' => $this->postPerpage, 'page' => $page]);

        $postDtos = $this->postService->getListPostsPerPage($request, PostService::TYPE_USER,$this->userService);
        if (parent::checkIsString($postDtos)) {
            $posts = new Collection();
            $total_page = 0;
            $current_page = 0;
        } else {
            $posts = new Collection($postDtos->data);
            $total_page = $postDtos->last_page;
            $current_page = $postDtos->current_page;
        }
        
        return view('post.list-post.index', [
            "categories" => $categories,
            "position_logo" => parent::getPositionLogo($categories),
            "list_post" => $posts,
            "total_page" => $total_page,
            "current_page" => $current_page,
            "user_name" => $user_name,
            "cart" => $cart
        ]);
    }

    public function postDetail(Request $request, $slug)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        if(parent::checkMaintenance($user) == "off"){
            return redirect()->route('maintenance');
        }
        parent::checkUserInPage($user->roles);
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
                return redirect()->route('login')->with('token_expired', "Phiên đăng nhập hết hạn");
            } 
        }

        $request->merge(['post_slug' => $slug]);
        $post = $this->postService->getPostBySlug($request,$this->userService);
        if(parent::checkIsString($categories) || parent::checkIsString($post)){
            abort(404);
        }

        return view('post.detail-post.index', [
            "categories" => $categories,
            "position_logo" => parent::getPositionLogo($categories),
            "user_name" => $user_name,
            "cart" => $cart,
            "post" => $post
        ]);
    }
}