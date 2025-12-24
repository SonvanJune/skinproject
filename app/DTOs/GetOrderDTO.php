<?php

namespace App\DTOs;

use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Data Transfer Object for representing an order.
 */
class GetOrderDTO
{
    /**
     * The ID of the order.
     *
     * @var string
     */
    public string $order_id;

    /**
     * The user who placed the order.
     *
     * @var GetUserDTO
     */
    public GetUserDTO $user;

    /**
     * The cart associated with the order.
     *
     * @var GetCartDTO
     */
    public GetCartDTO $cart;

    /**
     * The coupon applied to the order.
     *
     * @var GetCouponDTO
     */
    public ?GetCouponDTO $coupon;

    /**
     * The total price of the order.
     *
     * @var string
     */
    public string $total_price;

    /**
     * The payment method used for the order.
     *
     * @var string
     */
    public string $order_payment;

    /** @var string The update time of order */
    public string $updated_at;

    /** @var string The status of order */
    public string $status;

    /**
     * @var array
     */
    public array $discounts = [];

    public string $vat_detail;
    public string $vat_value;

    /**
     * GetOrderDTO constructor.
     *
     * @param string $order_id The ID of the order.
     * @param GetUserDTO $user The user who placed the order.
     * @param GetCartDTO $cart The cart associated with the order.
     * @param string $total_price The total price of the order.
     * @param string $order_payment The payment method used for the order.
     * @param GetCouponDTO $coupon The coupon applied to the order.
     */
    public function __construct(
        string $order_id,
        GetUserDTO $user,
        GetCartDTO $cart,
        string $total_price,
        string $order_payment,
        ?GetCouponDTO $coupon,
        string $updated_at,
        string $status,
        ?array $discounts,
        string $vat_detail,
        string $vat_value
    ) {
        $this->order_id = $order_id;
        $this->user = $user;
        $this->cart = $cart;
        $this->total_price = $total_price;
        $this->order_payment = $order_payment;
        $this->coupon = $coupon;
        $this->updated_at = $updated_at;
        $this->status = $status;
        $this->discounts = $discounts ?? [];
        $this->vat_detail = $vat_detail;
        $this->vat_value = $vat_value;
    }

    /**
     * Creates a new GetOrderDTO from an Order model.
     *
     * @param  Order  $order  The Order model instance.
     * @param  string  $user_id The ID of the user who placed the order.
     * @return static  A new GetOrderDTO instance.
     */
    public static function fromModel(Order $order, $user_id): static
    {

        $user = User::find($user_id);
        $cart = Cart::find($order->cart_id);
        return new static(
            $order->order_id,
            GetUserDTO::fromModel($user),
            GetCartDTO::fromModel($cart, $user_id),
            $order->order_price,
            $order->order_payment,
            $order->coupon ? GetCouponDTO::fromModel($order->coupon) : null,
            $order->updated_at,
            $order->order_status,
            self::getDiscountOfOrder($order),
            $order->vat_detail,
            $order->vat_value
        );
    }

    /**
     * Creates an array of GetOrderDTO from a collection of Order models.
     *
     * @param  Collection  $orders  The collection of Order model instances.
     * @param string $userId The ID of the user who placed the orders.
     * @return array  An array of GetOrderDTO instances.
     */
    public static function fromModels(Collection $orders, string $userId): array
    {
        $result = [];
        foreach ($orders as $order) {
            $user = User::find($userId);
            $userDTO = GetUserDTO::fromModel($user);
            $cart = Cart::find($order->cart_id);
            $result[] = new static(
                $order->order_id,
                $userDTO,
                GetCartDTO::fromModel($cart, $userId),
                $order->order_price,
                $order->order_payment,
                $order->coupon ? GetCouponDTO::fromModel($order->coupon) : null,
                $order->updated_at,
                $order->order_status,
                self::getDiscountOfOrder($order),
                $order->vat_detail,
                $order->vat_value
            );
        }
        return $result;
    }

    /**
     * Get discounts of order
     * @return array  An array of GetOrderDTO instances.
     */
    public static function getDiscountOfOrder(Order $order): array
    {
        $result = [];
        $orders = DB::table('orders_coupons')
            ->where('order_id', $order->order_id)->get();
        foreach ($orders as $order) {
            $coupon = Coupon::find($order->coupon_id);
            $priceSale = (float)($coupon->coupon_price ? $coupon->product->product_price - $coupon->coupon_price : $coupon->product->product_price - ($coupon->product->product_price * $coupon->coupon_per_hundred / 100));
            $result += [
                $coupon->product_id => $priceSale
            ];
        }
        return $result;
    }
}
