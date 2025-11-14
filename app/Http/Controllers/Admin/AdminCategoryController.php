<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\CategoryService;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminCategoryController extends Controller
{
    protected $categoryService;
    protected $userService;
    public function __construct(CategoryService $categoryService, UserService $userService)
    {
        $this->categoryService = $categoryService;
        $this->userService = $userService;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): View
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        parent::checkAdminInPage($user);
        $categories = $this->categoryService->getListCategoryPerPage($request, CategoryService::ROLE_ADMIN);
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $category_slug = null): View
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        parent::checkAdminInPage($user);
        $categories = $this->categoryService->getListCategoryPerPage($request, CategoryService::ROLE_ADMIN, true);
        return view('admin.categories.create', ['categories' => $categories, 'parent_slug' => $category_slug]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): RedirectResponse
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        parent::checkAdminInPage($user);
        $result =  $this->categoryService->createCategory($request);
        if (is_object($result) && isset($result->name)) {
            return redirect()->route('admin.categories')
                ->with('success', "Category created successfully!");
        } else {
            return redirect()->route('admin.categories')
                ->with('error',  $result);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $category_slug): View
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        parent::checkAdminInPage($user);
        $request->merge(['category_slug' => $category_slug]);
        $category = $this->categoryService->getCategoryBySlug($request, CategoryService::ROLE_ADMIN);
        if (parent::checkIsString($category)) {
            abort(404);
        }
        $categories = $this->categoryService->getListCategoryPerPage($request, CategoryService::ROLE_ADMIN, true);
        return view('admin.categories.edit', ['category' => $category, 'categories' => $categories]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request): RedirectResponse
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        parent::checkAdminInPage($user);
        $result =  $this->categoryService->updateCategory($request);
        if (parent::checkIsString($result)) {
            return redirect()->route('admin.categories')
                ->with('error', $result);
        }
        return redirect()->route('admin.categories')
            ->with('success', "Category updated successfully!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $category_slug): RedirectResponse
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        parent::checkAdminInPage($user);
        $request->merge(['category_slug' => $category_slug]);
        $result = $this->categoryService->deleteCategory($request);
        if (parent::checkIsString($result)) {
            return redirect()->route('admin.categories')->with('error', $result);
        }
        return redirect()->route('admin.categories')->with('success', $result);
    }
}
