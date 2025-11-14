<?php

namespace App\Http\Controllers;

use App\DTOs\GetCouponDTO;
use App\DTOs\GetProductDTO;
use App\Models\User;
use App\Services\CouponService;
use App\Services\UserService;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    protected $userService;
    protected $couponService;

    public function __construct(UserService $userService, CouponService $couponService)
    {
        $this->userService = $userService;
        $this->couponService = $couponService;
    }

    public function applyCoupon(Request $request)
    {

        $user = parent::checkTokenWhenReload($request, $this->userService, true);
        if(parent::checkMaintenance($user) == "off"){
            return redirect()->route('maintenance');
        }
        $applyCoupon = $this->couponService->applyCoupon($request, $user->user_id);
        $price = $request->input('priceCart');
        if ($user) {
            if (parent::checkIsString($applyCoupon)) {
                return redirect()->back()->with('error', $applyCoupon);
            } else {
                $priceCoupon = $this->mathPrice($applyCoupon, $price, $user);
                $typeCoupon = "";
                $priceAfterUseCouponForProduct = 0;
                $productCouponId = "";
                if ($priceCoupon != $price && $applyCoupon->product) {
                    $typeCoupon = "couponProduct";
                    $priceAfterUseCouponForProduct = $this->getPriceAfterUseCouponForProduct($applyCoupon, $price, $user);
                    $productCouponId = $this->getPriceAfterUseCouponForProduct($applyCoupon, $price, $user,true);
                } else {
                    $typeCoupon = "couponOrder";
                }
                return redirect()->back()->with([
                    'success' => 'Coupon applied successfully',
                    'coupon' => $applyCoupon,
                    'priceCoupon' => $priceCoupon,
                    'typeCoupon' => $typeCoupon,
                    'priceAfterUseCouponForProduct' => $priceAfterUseCouponForProduct,
                    'productCouponId' => $productCouponId
                ]);
            }
        }
    }

    public function mathPrice(GetCouponDTO $coupon, $price, User $user)
    {
        $total = 0;
        if ($coupon->product) {
            foreach ($user->carts as $cart) {
                if ($cart->cart_status == 1) {
                    foreach ($cart->products as $product) {
                        if ($product->product_id == $coupon->product->product_id) {
                            $priceSaleProduct = GetProductDTO::mathPriceSale($product);
                            $priceAfterDeleteDiscount = $price - $priceSaleProduct;
                            $priceUseCouponProduct = $coupon->coupon_price ? $product->product_price - $coupon->coupon_price : $product->product_price - ($product->product_price * $coupon->coupon_per_hundred / 100);
                            $total = $priceAfterDeleteDiscount + $priceUseCouponProduct;
                        }
                    }
                }
            }
        } else {
            $total = $coupon->coupon_price ? $price - $coupon->coupon_price : $price - ($price * $coupon->coupon_per_hundred / 100);
        }
        return $total;
    }

    public function getPriceAfterUseCouponForProduct(GetCouponDTO $coupon, $price, User $user, $getProductId = false){
        $total = 0;
        if ($coupon->product) {
            foreach ($user->carts as $cart) {
                if ($cart->cart_status == 1) {
                    foreach ($cart->products as $product) {
                        if ($product->product_id == $coupon->product->product_id) {
                            if($getProductId == true){
                                return $coupon->product->product_id;
                            }
                            $total = $coupon->coupon_price ? $product->product_price - $coupon->coupon_price : $product->product_price - ($product->product_price * $coupon->coupon_per_hundred / 100);
                        }
                    }
                }
            }
        }
        return $total;
    }
}
