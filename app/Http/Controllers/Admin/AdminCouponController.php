<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Services\CouponService;
use App\Services\ProductService;
use App\Services\RoleService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminCouponController extends Controller
{
    protected $couponService;
    protected $userService;
    protected $productService;
    public function __construct(CouponService $couponService, UserService $userService, ProductService $productService){
        $this->couponService = $couponService;
        $this->userService = $userService;
        $this->productService = $productService;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request):View
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        parent::checkAdminInPage($user);
        $paginatedDTO = $this->couponService->getListCouponPerPage($request, CouponService::ROLE_ADMIN);
        return view('admin.coupons.index', [
            "paginatedDTO" => $paginatedDTO
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request):View
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        parent::checkAdminInPage($user);
        $request->merge(['per_page'=> 1000]);
        $products = $this->productService->getListProductPerPage($request, ProductService::ROLE_ADMIN, null);
        return view('admin.coupons.create', [
            "products" => $products
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $user = parent::checkTokenWhenReload($request, $this->userService);
        parent::checkAdminInPage($user);
        $addCoupon = $this->couponService->createCoupon($request);
        if(parent::checkIsString($addCoupon)){
            return redirect()->back()->with('error', $addCoupon);
        }
        return redirect()->route('admin.coupons')->with('success', 'Coupon created successfully');
    }

    /**
     * Edit
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id):View
    {
        $user = parent::checkTokenWhenReload($request, $this->userService);
        parent::checkAdminInPage($user);
        $request->merge(['coupon_id'=> $id]);
        $coupon = $this->couponService->getCouponByCouponId($request,CouponService::ROLE_ADMIN);
        $products = $this->productService->getListProductPerPage($request, ProductService::ROLE_ADMIN, null);
        $productSelected = null;
        if($coupon->product) {
            $request->merge(['post_slug'=> $coupon->product->product_slug]);
            $productSelected = $this->productService->getProductBySlug($request, CouponService::ROLE_ADMIN, null);
        }
        return view('admin.coupons.edit', [
            "coupon" => $coupon,
            "products" => $products,
            "productSelected" => $productSelected
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     */
    public function update(Request $request){
        $user = parent::checkTokenWhenReload($request, $this->userService);
        parent::checkAdminInPage($user);
        $updateCoupon = $this->couponService->updateCoupon($request);
        if(parent::checkIsString($updateCoupon)){
            return redirect()->back()->with('error', $updateCoupon);
        }
        return redirect()->route('admin.coupons')->with('success', 'Coupon updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request){
        $user = parent::checkTokenWhenReload($request, $this->userService);
        parent::checkAdminInPage($user);
        $delete = $this->couponService->deleteCoupon($request);
        if(parent::checkIsString($delete)){
            return redirect()->back()->with('error', $delete);
        }
        else{
            return redirect()->route('admin.coupons')->with('success', $delete->message);
        }
    }
}