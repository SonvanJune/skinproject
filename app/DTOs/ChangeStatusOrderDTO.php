<?php

namespace App\DTOs;

use App\Models\Cart;
use App\Models\Order;
use App\Models\User;

/**
 * Data Transfer Object for representing an order with a changed status.
 */
class ChangeStatusOrderDTO
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
    public GetCouponDTO $coupon;

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

    /**
     * The status of the order.
     *
     * @var int
     */
    public int $order_status;

    /**
     * CreateOrderDTO constructor.
     *
     * @param string $order_id The ID of the order.
     * @param GetUserDTO $user The user who placed the order.
     * @param GetCartDTO $cart The cart associated with the order.
     * @param string $total_price The total price of the order.
     * @param string $order_payment The payment method used for the order.
     * @param GetCouponDTO $coupon The coupon applied to the order.
     * @param int $order_status The status of the order.
     */
    public function __construct(
        string $order_id,
        GetUserDTO $user,
        GetCartDTO $cart,
        string $total_price,
        string $order_payment,
        GetCouponDTO $coupon,
        int $order_status
    ) {
        $this->order_id = $order_id;
        $this->user = $user;
        $this->cart = $cart;
        $this->total_price = $total_price;
        $this->order_payment = $order_payment;
        $this->coupon = $coupon;
        $this->order_status = $order_status;
    }

    /**
     * Creates a new ChangeStatusOrderDTO from an Order model.
     *
     * @param  Order  $order  The Order model instance.
     * @param  string  $user_id The ID of the user who placed the order.
     * @return static  A new ChangeStatusOrderDTO instance.
     */
    public static function fromModel(Order $order, $user_id): static
    {
        $user = User::find($user_id);
        $userDTO = GetUserDTO::fromModel($user);
        $cart = Cart::find($order->cart_id);

        return new static(
            $order->order_id,
            GetUserDTO::fromModel($user),
            GetCartDTO::fromModel($cart, $userDTO),
            $order->order_price,
            $order->order_payment,
            GetCouponDTO::fromModel($order->coupon),
            $order->order_status
        );
    }
}
