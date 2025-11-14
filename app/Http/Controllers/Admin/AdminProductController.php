<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\CategoryService;
use App\Services\ProductService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminProductController extends Controller
{
    protected $productService;
    protected $userService;
    protected $categoyService;

    public function __construct(ProductService $productService, UserService $userService, CategoryService $categoryService)
    {
        $this->productService = $productService;
        $this->userService = $userService;
        $this->categoyService = $categoryService;
    }

    public function index(Request $request)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        parent::checkAdminInPage($user);

        $page = $request->query('page');
        $per_page = $request->query('per_page');

        if (!$page || !is_numeric($page) || $page < 1) {
            $page = 1;
        }

        if (!$per_page || !is_numeric($per_page) || $per_page < 1) {
            $per_page = UserService::PER_PAGE;
        }

        $request->merge(["page" => $page, "per_page" => $per_page]);
        $paginatedDTO = $this->productService->getListProductPerPage($request, ProductService::ROLE_ADMIN);
        return view(
            'admin.products.index',
            compact('paginatedDTO')
        );
    }

    public function products(Request $request)
    {
        $products = $this->productService->getListProductPerPage($request, 1);
        return response()->json(['products' => $products]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request): View
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        parent::checkAdminInPage($user);
        $categories = $this->categoyService->getListCategoryPerPage($request, CategoryService::ROLE_ADMIN, true);
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        parent::checkAdminInPage($user);
        $result =  $this->productService->createProduct($request, $user->user_id);
        if (parent::checkIsString($result)) {
            return redirect()->route('admin.products')
                ->with('error', $result);
        }
        return redirect()->route('admin.products')
            ->with('success', "Product created successfully!");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $post_slug)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        parent::checkAdminInPage($user);
        $request->merge(['post_slug' => $post_slug]);

        $product = $this->productService->getProductBySlug($request, CategoryService::ROLE_ADMIN, null);
        if(parent::checkIsString($product)){
            abort(404);
        }
        $categories = $this->categoyService->getListCategoryPerPage($request, CategoryService::ROLE_ADMIN, true);
        return view('admin.products.edit', ['product' => $product, 'categories' => $categories]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        parent::checkAdminInPage($user);
        $result =  $this->productService->updateProduct($request);
        if (parent::checkIsString($result)) {
            return redirect()->route('admin.products')
                ->with('error', $result);
        }
        return redirect()->route('admin.products')
            ->with('success', $result->message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $post_slug)
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        parent::checkAdminInPage($user);
        $request->merge(['post_slug' => $post_slug]);
        $result = $this->productService->deleteProduct($request);
        if (parent::checkIsString($result)) {
            return redirect()->route('admin.products')->with('error', $result);
        }

        return redirect()->route('admin.products')->with('success', $result->message);
    }
}
