<?php

namespace App\Services;

use App\DTOs\CreateCartDTO;
use App\DTOs\GetCartDTO;
use App\DTOs\InsertProductToCartDTO;
use App\DTOs\RemoveProductCartDTO;
use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\Post;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

/**
 * Service class for managing shopping carts.
 */
class CartService
{
    /**
     * Cart status representing a deleted cart.
     * 
     * @var int
     */
    public const STATUS_DELETED = -1;

    /**
     * Cart status representing an inactive cart.
     * 
     * @var int
     */
    public const STATUS_INACTIVE = 0;

    /**
     * Cart status representing a purchased cart.
     * 
     * @var int
     */
    public const STATUS_ACTIVE = 1;

    /**
     * Cart status representing a purchased cart.
     * 
     * @var int
     */
    public const STATUS_BOUGHT = 2;

    /**
     * The user service instance.
     *
     * @var UserService
     */
    private UserService $userService;

    /**
     * Constructor for CartService.
     *
     * @param  UserService  $userService  The user service instance to inject.
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Performs common checks for cart operations (existence, ownership, status).
     *
     * @param  string  $cartId  The ID of the cart.
     * @param  string  $userId  The ID of the user.
     * @return string|null  Returns an error message if a check fails, otherwise null.
     */
    private function performCartChecks(string $cartId, string $userId): ?string
    {
        $cart = Cart::find($cartId);

        if (!$cart) {
            return "Cart not found.";
        }

        if ($cart->user_id != $userId) {
            return "User does not have permission to modify this cart.";
        }

        if ($cart->cart_status == self::STATUS_BOUGHT) {
            return "Cannot modify a cart that has been purchased.";
        }

        if ($cart->cart_status == self::STATUS_DELETED) {
            return "Cannot modify a deleted cart.";
        }

        return null;
    }

    /**
     * Creates a new cart for the user if they don't already have an inactive one.
     *
     * @param  string  $user_id  The ID of the user.
     * @return CreateCartDTO|string  Returns the cart DTO if successful, otherwise an error message.
     */
    public function createCart($user_id): CreateCartDTO|string
    {
        $user = $this->userService->readUserInformation(new Request(["user_id" => $user_id]));

        if (is_string($user)) {
            return $user;
        }

        $isHasCart = User::find($user_id)->carts()->where('cart_status', self::STATUS_ACTIVE)->first();
        if ($isHasCart) {
            return CreateCartDTO::fromModel($isHasCart, $user);
        }

        DB::beginTransaction();
        try {
            $cart = new Cart();
            $cart->cart_id = (string) Str::uuid();
            $cart->user_id = $user_id;
            $cart->cart_status = self::STATUS_ACTIVE;

            $flag = $cart->save();

            DB::commit();

            if (!$flag) {
                return "Cannot create a new cart";
            }

            return CreateCartDTO::fromModel($cart, $user);
        } catch (Exception $e) {
            DB::rollBack();

            return 'Failed to create cart: ' . $e->getMessage();
        }
    }

    /**
     * Removes a product from the user's cart.
     *
     * @param  Request  $request  The HTTP request object containing cart_id and product_slug.
     * @param  string  $user_id  The ID of the user making the request.
     * @return RemoveProductCartDTO|string  Returns a RemoveProductCartDTO if successful, otherwise an error message.
     */
    public function removeProductFromCart(Request $request, string $user_id): RemoveProductCartDTO|string
    {
        $validator = Validator::make($request->all(), [
            'cart_id' => 'required|uuid',
            'product_slug' => 'required|string',
        ]);

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        $cartId = $request->input('cart_id');
        $productSlug = $request->input('product_slug');

        $cart = Cart::find($cartId);
        $product = Post::where('post_slug', $productSlug)->first()?->product;

        if (!$product) {
            return "Product not found.";
        }

        $errorMessage = $this->performCartChecks($cartId, $user_id);
        if ($errorMessage) {
            return $errorMessage;
        }

        $cartProduct = CartProduct::where('cart_id', $cartId)
            ->where('product_id', $product->product_id)
            ->first();

        if (!$cartProduct) {
            return "Product not found in cart.";
        }

        $user = $this->userService->readUserInformation(new Request(["user_id" => $user_id]));

        if (is_string($user)) {
            return $user;
        }

        DB::beginTransaction();
        try {
            $flag = CartProduct::where('cart_id', $cartId)
                ->where('product_id', $product->product_id)->delete();

            DB::commit();

            if (!$flag) {
                return "Cannot remove product from cart.";
            }

            $cart = $this->getCartWithProducts($cartId);

            return RemoveProductCartDTO::fromModel($cart, $user);
        } catch (Exception $e) {
            DB::rollBack();
            return 'Failed to remove product from cart: ' . $e->getMessage();
        }
    }

    /**
     * Retrieves a cart with its associated products.
     *
     * @param  string  $cartId  The ID of the cart.
     * @return Cart|null  The cart with its products, or null if not found.
     */
    private function getCartWithProducts($cartId): ?Cart
    {
        return Cart::with('products')->find($cartId);
    }

    /**
     * Retrieves all carts associated with a user and returns an array of GetCartDTO.
     *
     * @param  string  $userId  The ID of the user.
     * @return array  An array of GetCartDTO representing the user's carts.
     */
    public function getCartsByUser($userId)
    {
        $cart = Cart::where('user_id', $userId)->where('cart_status', self::STATUS_ACTIVE)->first();
        if($cart == null){
            return "Cart not found";
        }
        return GetCartDTO::fromModel($cart, $userId);
    }

    /**
     * Deletes a cart by changing its status to deleted.
     *
     * @param  Request  $request  The HTTP request object containing cart_id.
     * @param  string  $user_id  The ID of the user making the request.
     * @return bool|string  Returns true if successful, otherwise an error message.
     */
    public function deleteCart(Request $request, string $user_id): bool|string
    {
        $validator = Validator::make($request->all(), [
            'cart_id' => 'required|uuid'
        ]);

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        $cartId = $request->input('cart_id');

        $errorMessage = $this->performCartChecks($cartId, $user_id);
        if ($errorMessage) {
            return $errorMessage;
        }

        $cart = Cart::find($cartId);

        DB::beginTransaction();
        try {
            $cart->cart_status = self::STATUS_DELETED;
            $flag = $cart->save();

            DB::commit();

            if (!$flag) {
                return "Cannot delete cart.";
            }

            return true;
        } catch (Exception $e) {
            DB::rollBack();

            return 'Failed to delete cart: ' . $e->getMessage();
        }
    }

    /**
     * Adds a product to the user's cart.
     *
     * @param  Request  $request  The HTTP request object containing cart_id and product_slug.
     * @param  string  $user_id  The ID of the user making the request.
     * @return InsertProductToCartDTO|string  Returns an InsertProductToCartDTO if successful, otherwise an error message.
     */
    public function insertProductToCart(Request $request, string $user_id): InsertProductToCartDTO|string
    {
        $validator = Validator::make($request->all(), [
            'cart_id' => 'nullable|uuid',
            'product_slug' => 'required|string',
        ]);

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        $cartId = null;

        if (!$request->input('cart_id')) {
            $cartId = $this->createCart($user_id)->cart_id;
        } else {
            $cartId = $request->input('cart_id');
        }

        $productSlug = $request->input('product_slug');

        $cart = Cart::find($cartId);
        $product = Post::where('post_slug', $productSlug)->first()?->product;

        if (!$product) {
            return "Product not found.";
        }

        $errorMessage = $this->performCartChecks($cartId, $user_id);

        if ($errorMessage) {
            return $errorMessage;
        }

        $cartProduct = CartProduct::where('cart_id', $cartId)
            ->where('product_id', $product->product_id)
            ->first();

        if ($cartProduct) {
            return "Product already exists in cart.";
        }

        $user = $this->userService->readUserInformation(new Request(["user_id" => $user_id]));

        if (is_string($user)) {
            return $user;
        }

        DB::beginTransaction();
        try {
            $cartProduct = new CartProduct();
            $cartProduct->cart_id = $cartId;
            $cartProduct->product_id = $product->product_id;
            $flag = $cartProduct->save();

            DB::commit();

            if (!$flag) {
                return "Cannot add product to cart.";
            }

            $cart = $this->getCartWithProducts($cartId);

            return InsertProductToCartDTO::fromModel($cart, $user);
        } catch (Exception $e) {
            DB::rollBack();
            return 'Failed to add product to cart: ' . $e->getMessage();
        }
    }

    /**
     * Calculates the total price of a collection of products.
     *
     * @param  Collection  $products  The collection of Product models.
     * @return string  The total price as a string.
     */
    public static function totalPrice(Collection $products): string
    {
        $totalPrice = 0;
        foreach ($products as $product) {
            if ($product->coupons()->exists()) {
                $coupons = $product->coupons()->get();
                $total = 0;
                foreach ($coupons as $coupon) {
                    if ($coupon->product_id && !$coupon->coupon_code && now() >= $coupon->coupon_release && now() < $coupon->coupon_expired) {
                        $total = (float)($coupon->coupon_price ? $product->product_price - $coupon->coupon_price : $product->product_price - ($product->product_price * $coupon->coupon_per_hundred / 100));
                        break;
                    }
                    else {
                        $total = (float)$product->product_price;
                    }
                }
                $totalPrice += $total;
            }
            else {
                $totalPrice += (float)$product->product_price;
            }
        }
        return $totalPrice;
    }
}
