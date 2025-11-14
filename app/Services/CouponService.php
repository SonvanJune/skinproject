<?php

namespace App\Services;

use App\DTOs\CreateCouponDTO;
use App\DTOs\DeleteCouponDTO;
use App\DTOs\GetCouponDTO;
use App\DTOs\PaginatedDTO;
use App\DTOs\UpdateCouponDTO;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderCoupon;
use App\Models\Product;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CouponService
{
    // Constants defining coupon limits, sorting options, user roles, and pagination settings
    public const COUPON_PER_HUNDRED_MAX = 100;
    public const COUPON_PER_HUNDRED_MIN = 0;
    public const COUPON_PRICE_MIN = 0;
    public const NO_SORT = 0;
    public const SORT_ASC = 1;
    public const SORT_DESC = 2;
    public const ROLE_ADMIN = 1;
    public const ROLE_USER = 2;
    public const PAGE_SIZE_DEFAULT = 1;
    public const PER_PAGE_DEFAULT = 15;
    public const SUB_DAY = 2;

    /**
     * Create a new coupon.
     *
     * @param Request $request
     * @return CreateCouponDTO|string
     */
    public function createCoupon(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'coupon_name' => 'nullable|string',
            'coupon_code' => 'nullable|string',
            'coupon_release' => 'nullable|date',
            'coupon_expired' => 'nullable|date',
            'coupon_per_hundred' => 'nullable|numeric',
            'coupon_price' => 'nullable|string',
            'product_id' => 'nullable|string'
        ]);

        $product = Product::find($request->input('product_id'));


        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        $validateProductHaveCouponAndDiscount = $this->validateProductHaveCouponAlready($request);
        if ($validateProductHaveCouponAndDiscount) {
            return $validateProductHaveCouponAndDiscount;
        }

        $validateCouponCodeAndNameError = $this->validateCouponName($request);
        if ($validateCouponCodeAndNameError) {
            return $validateCouponCodeAndNameError;
        }

        $validateCouponCodeAndNameUniquenessError = $this->validateCouponCodeAndNameUniqueness($request);
        if ($validateCouponCodeAndNameUniquenessError) {
            return $validateCouponCodeAndNameUniquenessError;
        }

        $validateReleaseDateError = $this->validateReleaseDate($request);
        if ($validateReleaseDateError) {
            return $validateReleaseDateError;
        }

        $validateExpirationDateError = $this->validateExpirationDate($request);
        if ($validateExpirationDateError) {
            return $validateExpirationDateError;
        }

        if ($request->input('coupon_per_hundred') && $request->input('coupon_price')) {
            return 'Coupon rate and coupon price cannot be used at the same time';
        }

        if (!$request->input('product_id') && !$request->input('coupon_code')) {
            return 'Coupon can not be created, please try again';
        }

        $validateCouponPerHundredError = $this->validateCouponPerHundred($request);
        if ($validateCouponPerHundredError) {
            return $validateCouponPerHundredError;
        }

        $validateCouponPriceError = $this->validateCouponPrice($request, $product);
        if ($validateCouponPriceError) {
            return $validateCouponPriceError;
        }


        if ($request->input('product_id')) {
            // Create and save the coupon
            DB::beginTransaction();
            try {
                $coupon = new Coupon();
                $coupon->coupon_id = Str::uuid()->toString();
                $coupon->coupon_name = $request->input('coupon_name') ?: null;
                $coupon->coupon_code = $request->input('coupon_code') ?: null;
                $coupon->coupon_release = $request->input('coupon_release') ?: now();
                $coupon->coupon_expired = $request->input('coupon_expired') ?: null;
                $coupon->coupon_per_hundred = $request->input('coupon_per_hundred') ?: null;
                $coupon->coupon_price = $request->input('coupon_price') ?: null;
                $coupon->product_id = $request->input('product_id');
                $coupon->save();
                DB::commit();
                return CreateCouponDTO::fromModel($coupon);
            } catch (\Exception $e) {
                DB::rollBack();
                return 'Failed to create coupon: ' . $e->getMessage();
            }
        } else {
            // Create and save the coupon
            DB::beginTransaction();
            try {
                $coupon = new Coupon();
                $coupon->coupon_id = Str::uuid()->toString();
                $coupon->coupon_name = $request->input('coupon_name') ?: null;
                $coupon->coupon_code = $request->input('coupon_code') ?: null;
                $coupon->coupon_release = $request->input('coupon_release') ?: now();
                $coupon->coupon_expired = $request->input('coupon_expired') ?: null;
                $coupon->coupon_per_hundred = $request->input('coupon_per_hundred') ?: null;
                $coupon->coupon_price = $request->input('coupon_price') ?: null;
                $coupon->product_id = null;
                $coupon->save();
                DB::commit();
                return CreateCouponDTO::fromModel($coupon);
            } catch (\Exception $e) {
                DB::rollBack();
                return 'Failed to create coupon: ' . $e->getMessage();
            }
        }
    }

    /**
     * Update an existing coupon.
     *
     * @param Request $request
     * @return UpdateCouponDTO|string
     */
    public function updateCoupon(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'coupon_id' => 'nullable|string',
            'coupon_name' => 'nullable|string',
            'coupon_code' => 'nullable|string',
            'coupon_release' => 'nullable|date',
            'coupon_expired' => 'nullable|date',
            'coupon_per_hundred' => 'nullable|numeric',
            'coupon_price' => 'nullable|string',
            'product_id' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        $coupon = Coupon::find($request->input('coupon_id'));
        if (!$coupon) {
            return 'Coupon not found';
        }

        $validateCouponIsUsed = $this->validateCouponIsUsed($request, $coupon);
        if ($validateCouponIsUsed) {
            return $validateCouponIsUsed;
        }

        $validateProductHaveCouponAndDiscount = $this->validateProductHaveCouponAlready($request, false, $coupon);
        if ($validateProductHaveCouponAndDiscount) {
            return $validateProductHaveCouponAndDiscount;
        }

        $validateCouponCodeAndNameError = $this->validateCouponName($request);
        if ($validateCouponCodeAndNameError) {
            return $validateCouponCodeAndNameError;
        }

        $validateCouponCodeAndNameUniquenessError = $this->validateCouponCodeAndNameUniqueness($request, true);
        if ($validateCouponCodeAndNameUniquenessError) {
            return $validateCouponCodeAndNameUniquenessError;
        }

        $validateExpirationDateError = $this->validateExpirationDate($request);
        if ($validateExpirationDateError) {
            return $validateExpirationDateError;
        }

        if ($request->input('coupon_per_hundred') && $request->input('coupon_price')) {
            return 'Coupon rate and coupon price cannot be used at the same time';
        }

        if (!$request->input('product_id') && !$request->input('coupon_code')) {
            return 'Coupon can not be created, please try again';
        }

        $validateCouponPerHundredError = $this->validateCouponPerHundred($request);
        if ($validateCouponPerHundredError) {
            return $validateCouponPerHundredError;
        }

        if ($request->input('product_id')) {
            $product = Product::find($request->input('product_id'));

            $validateCouponPriceError = $this->validateCouponPrice($request, $product);
            if ($validateCouponPriceError) {
                return $validateCouponPriceError;
            }
        }


        if ($request->input('product_id')) {
            DB::beginTransaction();
            try {
                $coupon->coupon_id = Str::uuid()->toString();
                $coupon->coupon_name = $request->input('coupon_name')?: null;
                $coupon->coupon_code = $request->input('coupon_code')?: null;
                $coupon->coupon_release = $request->input('coupon_release') ?: $coupon->coupon_release;
                $coupon->coupon_expired = $request->input('coupon_expired') ?: $coupon->coupon_expired;
                $coupon->coupon_per_hundred = $request->input('coupon_per_hundred') ?: null;
                $coupon->coupon_price = $request->input('coupon_price') ?: null;
                $coupon->product_id = $request->input('product_id');
                $coupon->save();
                DB::commit();
                return UpdateCouponDTO::fromModel($coupon);
            } catch (\Exception $e) {
                DB::rollBack();
                return 'Failed to update coupon: ' . $e->getMessage();
            }
        } else {
            // Create and save the coupon
            DB::beginTransaction();
            try {
                $coupon->coupon_id = Str::uuid()->toString();
                $coupon->coupon_name = $request->input('coupon_name') ?: null;
                $coupon->coupon_code = $request->input('coupon_code') ?: null;
                $coupon->coupon_release = $request->input('coupon_release');
                $coupon->coupon_expired = $request->input('coupon_expired') ?: null;
                $coupon->coupon_per_hundred = $request->input('coupon_per_hundred') ?: null;
                $coupon->coupon_price = $request->input('coupon_price') ?: null;
                $coupon->product_id = null;
                $coupon->save();
                DB::commit();
                return UpdateCouponDTO::fromModel($coupon);
            } catch (\Exception $e) {
                DB::rollBack();
                return 'Failed to update coupon: ' . $e->getMessage();
            }
        }
    }

    /**
     * Delete an existing coupon.
     *
     * @param Request $request
     * @return DeleteCouponDTO|string
     */
    public function deleteCoupon(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'coupon_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        $coupon = Coupon::find($request->input('coupon_id'));
        if (!$coupon) {
            return 'Coupon not found';
        }

        $validateCouponIsUsed = $this->validateCouponIsUsed($request, $coupon);
        if ($validateCouponIsUsed) {
            return $validateCouponIsUsed;
        }

        DB::beginTransaction();
        try {
            $coupon->delete();
            DB::commit();
            return DeleteCouponDTO::fromModel('Coupon deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return 'Failed to delete coupon: ' . $e->getMessage();
        }
    }

    /**
     * Apply an existing coupon for order.
     *
     * @param Request $request
     * @return GetCouponDTO|string
     */
    public function applyCoupon(Request $request, string $user_id)
    {
        $validator = Validator::make($request->all(), [
            'coupon_code' => 'required|string',
        ]);

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        $user = User::find($user_id);
        if (!$user) {
            return 'User not found';
        }

        $coupon = Coupon::where('coupon_code', $request->input('coupon_code'))->first();
        if (!$coupon || $coupon->coupon_release > now()) {
            return 'Coupon not found';
        }

        if ($coupon->coupon_expired < now()) {
            return 'Coupon has expired';
        }

        $validateCouponIsUsed = $this->validateCouponIsUsedForUser($request, $coupon, $user);
        if ($validateCouponIsUsed) {
            return $validateCouponIsUsed;
        }

        if ($coupon->product) {
            $validateCouponIsValidWithCart = $this->validateUserHaveProductOfCoupon($request, $coupon, $user);
            if ($validateCouponIsValidWithCart) {
                return $validateCouponIsValidWithCart;
            }
        }

        return GetCouponDTO::fromModel($coupon);
    }

    /**
     * Attach coupon order to table
     *
     * @param Request $request
     * @return DeleteCouponDTO|string
     */
    public function attachCouponOrder(Request $request, string $order_id)
    {
        $validator = Validator::make($request->all(), [
            'cart_id' => 'required|uuid'
        ]);

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        $cart = Cart::with('products')->find($request->input('cart_id'));

        if (! $cart) {
            return 'Cart not found.';
        }

        DB::beginTransaction();
        try {
            foreach ($cart->products as $product) {
                if ($product->coupons()->exists()) {
                    foreach ($product->coupons as $coupon) {
                        if ($coupon->product_id && !$coupon->coupon_code && now() >= $coupon->coupon_release && now() < $coupon->coupon_expired) {
                            DB::table('orders_coupons')->insert([
                                'order_id' => $order_id,
                                'coupon_id' => $coupon->coupon_id
                            ]);
                        }
                    }
                }
            }

            DB::commit();

            return DeleteCouponDTO::fromModel("Add successfully");
        } catch (Exception $e) {
            DB::rollBack();
            return 'Failed to create order: ' . $e->getMessage();
        }
    }

    /**
     * Get a coupon by its ID, based on user role.
     *
     * @param Request $request
     * @param int $role
     * @return GetCouponDTO|string
     */
    public function getCouponByCouponId(Request $request, $role)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'coupon_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        // Retrieve the coupon based on the role
        $coupon = null;
        if ($role == CouponService::ROLE_USER) {
            $coupon = Coupon::where('coupon_code', $request->input('coupon_code'))
                ->where('coupon_release', '<=', now())
                ->where('coupon_expired', '>=', now())
                ->first();
            if (!$coupon) {
                return 'Coupon not found';
            }
        } else if ($role == CouponService::ROLE_ADMIN) {
            $coupon = Coupon::find($request->input('coupon_id'));
            if (!$coupon) {
                return 'Coupon not found';
            }
        } else {
            return 'You do not have permission to view the coupon';
        }

        return GetCouponDTO::fromModel($coupon);
    }

    /**
     * Get a paginated list of coupons based on user role.
     *
     * @param Request $request
     * @param int $role
     * @return PaginatedDTO|string
     */
    public function getListCouponPerPage(Request $request, $role)
    {
        $validator = Validator::make($request->all(), [
            'page' => 'nullable|integer',
            'per_page' => 'nullable|integer',
            'key' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        $perPage = $request->input('per_page') ?: CouponService::PER_PAGE_DEFAULT;
        $page = $request->input('page') ?: CouponService::PAGE_SIZE_DEFAULT;
        $skip = ($page - 1) * $perPage;
        $key = $request->input('key', '') ?? "";

        if ($role == CouponService::ROLE_USER) {
            $coupons = Coupon::where('coupon_name', 'LIKE', '%' . $key . '%')
                ->orWhere('coupon_code', 'LIKE', '%' . $key . '%')
                ->where('coupon_release', '<=', now())
                ->where('coupon_expired', '>=', now());
        } else if ($role == CouponService::ROLE_ADMIN) {
            $coupons = Coupon::where('coupon_name', 'LIKE', '%' . $key . '%')
                ->orWhere('coupon_code', 'LIKE', '%' . $key . '%')
                ->orderByRaw('
                CASE 
                WHEN coupon_expired >= NOW() THEN 1
                ELSE 2
                END
                ')
                ->orderBy('coupon_release', 'desc')
                ->get();
        } else {
            return 'You do not have permission to view the coupon';
        }

        $total = $coupons->count();

        $coupons = $coupons->skip($skip)->take($perPage);
        return PaginatedDTO::fromData(GetCouponDTO::fromModels($coupons), $page, $perPage, $total, $key ?? "");
    }
    /**
     * Validate coupon code and name uniqueness.
     * @param Request $request
     * @return string|null
     */
    public function validateCouponCodeAndNameUniqueness(Request $request, $isUpdate = false)
    {
        if ($request->input('coupon_code') && $request->input('coupon_code') != null && $request->input('coupon_code') != "" && !$request->input('coupon_id')) {
            $coupon = Coupon::where('coupon_code', $request->input('coupon_code'))->first();
            if ($coupon && $coupon->coupon_expired > now()) {
                return 'Coupon code already exists';
            }
        }

        if (!$isUpdate) {
            if ($request->input('coupon_name')) {
                $coupon = Coupon::where('coupon_name', $request->input('coupon_name'))->first();
                if ($coupon) {
                    return 'Coupon name already exists';
                }
            }
        }
        return null;
    }

    /**
     * Validate coupon code and name uniqueness.
     * @param Request $request
     * @return string|null
     */
    public function validateCouponIsUsed(Request $request, $coupon)
    {
        if ($coupon->coupon_code) {
            $couponUseInOrder = Order::where('coupon_id', $coupon->coupon_id)->first();
            if ($couponUseInOrder) {
                return 'Coupon code already used in order';
            }
        } else {
            $couponUseInOrderCoupon = OrderCoupon::where('coupon_id', $coupon->coupon_id)->first();
            if ($couponUseInOrderCoupon) {
                return 'Coupon code already used in order coupon';
            }
        }
        return null;
    }

    /**
     * Validate coupon code and name uniqueness.
     * @param Request $request
     * @return string|null
     */
    public function validateCouponIsUsedForUser(Request $request, $coupon, User $user)
    {
        $carts = $user->carts()->get();
        foreach ($carts as $cart) {
            if ($cart->order) {
                $order = $cart->order;
                if ($coupon->coupon_code) {
                    $couponUseInOrder = Order::where('coupon_id', $coupon->coupon_id)->where('order_id', $order->order_id)->first();
                    if ($couponUseInOrder) {
                        return 'Coupon code already used in order';
                    }
                }
            }
        }
        return null;
    }

    /**
     * Validate coupon code and name uniqueness.
     * @param Request $request
     * @return string|null
     */
    public function validateProductHaveCouponAlready(Request $request, $isCreate = true, $couponExist = null)
    {
        if ($request->input('product_id')) {
            $coupons = Coupon::where('product_id', $request->input('product_id'))->get();
            if ($coupons && $couponExist != null) {
                foreach ($coupons as $coupon) {
                    if ($coupon->coupon_code && $request->input('coupon_code') && $couponExist->coupon_id != $coupon->coupon_id) {
                        if (now() >= $coupon->coupon_release && now() < $coupon->coupon_expired) {
                            return 'The coupon overlaps with an existing coupon for this product';
                        }
                    }

                    if (!$coupon->coupon_code && !$request->input('coupon_code') && $couponExist->coupon_id != $coupon->coupon_id) {
                        if (now() >= $coupon->coupon_release && now() < $coupon->coupon_expired) {
                            return 'The discount overlaps with an existing discount for this product';
                        }
                    }
                }
            } else {
                foreach ($coupons as $coupon) {
                    if ($coupon->coupon_code && $request->input('coupon_code')) {
                        if ($request->input('coupon_expired') <= $coupon->coupon_expired || $request->input('coupon_release') <= $coupon->coupon_expired) {
                            return 'The new coupon overlaps with an existing coupon for this product';
                        }
                    }

                    if (!$coupon->coupon_code && !$request->input('coupon_code')) {
                        if ($request->input('coupon_expired') <= $coupon->coupon_expired || $request->input('coupon_release') <= $coupon->coupon_expired) {
                            return 'The new discount overlaps with an existing discount for this product';
                        }
                    }
                }
            }
        }
        return null;
    }

    /**
     * Validate coupon code and name uniqueness.
     * @param Request $request
     * @return string|null
     */
    public function validateUserHaveProductOfCoupon(Request $request, $coupon, User $user)
    {
        $carts = $user->carts()->get();
        $arr = [];
        foreach ($carts as $cart) {
            if ($cart->cart_status == 1) {
                foreach ($cart->products as $product) {
                    $arr[] = $product->product_id;
                }
            }
        }
        if (!in_array($coupon->product_id, $arr)) {
            return 'Coupon is not applicable to these products';
        }
        return null;
    }

    /**
     * Validate coupon code and name.
     * @param Request $request
     * @return string|null
     */
    public function validateCouponName(Request $request)
    {
        if ($request->input('coupon_name') && !$request->input('coupon_name')) {
            return 'Coupon name is required if coupon code is provided';
        }
        return null;
    }
    /**
     * Validate release date.
     * @param Request $request
     * @return string|null
     */
    public function validateReleaseDate(Request $request)
    {
        if ($request->input('coupon_release')) {
            if ($request->input('coupon_release') < now()) {
                return 'Release date must be greater than or equal to the current date';
            }
            if ($request->input('coupon_release') > $request->input('coupon_expired')) {
                return 'Release date must be less than the expiration date';
            }
            if ($request->input('coupon_release') == $request->input('coupon_expired')) {
                return 'Release date must be different from the expiration date';
            }
        }
        return null;
    }
    /**
     * Validate expiration date.
     * @param Request $request
     * @return string|null
     */
    public function validateExpirationDate(Request $request)
    {
        if ($request->input('coupon_expired')) {
            if ($request->input('coupon_expired') < now()) {
                return 'Expiration date must be greater than or equal to the current date';
            }
            if ($request->input('coupon_expired') < $request->input('coupon_release')) {
                return 'Expiration date must be greater than or equal to the release date';
            }
            if ($request->input('coupon_expired') == $request->input('coupon_release')) {
                return 'Expiration date must be different from the release date';
            }
        }
        return null;
    }
    /**
     * Validate discount rate per hundred.
     * @param Request $request
     * @return string|null
     */
    public function validateCouponPerHundred(Request $request)
    {
        if ($request->input('coupon_per_hundred')) {
            if ($request->input('coupon_per_hundred') < CouponService::COUPON_PER_HUNDRED_MIN) {
                return 'Discount rate must be greater than 0';
            }
            if ($request->input('coupon_per_hundred') > CouponService::COUPON_PER_HUNDRED_MAX) {
                return 'Discount rate must be less than 100';
            }
            if ($request->input('coupon_per_hundred') == CouponService::COUPON_PER_HUNDRED_MIN) {
                return 'Discount rate must be greater than 0';
            }
        }
        return null;
    }
    /**
     * Validate discount price.
     * @param Request $request
     * @return string|null
     */
    public function validateCouponPrice(Request $request, $product = null)
    {
        if ($request->input('coupon_price')) {
            if ($request->input('coupon_price') < CouponService::COUPON_PRICE_MIN) {
                return 'Discount price must be greater than 0';
            }
            if ($request->input('coupon_price') == CouponService::COUPON_PRICE_MIN) {
                return 'Discount price must be greater than 0';
            }
            if ($product) {
                if ($request->input('coupon_price') > $product->product_price) {
                    return 'Discount price must be less than or equal to the product price';
                }
            }
        }
        return null;
    }
}
