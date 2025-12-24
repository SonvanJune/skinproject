<?php

namespace App\DTOs;

use App\Models\Cart;
use App\Models\Order;
use App\Models\User;

/**
 * Data Transfer Object for representing a newly created order.
 */
class CreateOrderDTO
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
    public ?GetCouponDTO $coupon = null;

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
    public string $vat_detail;
    public string $vat_value;

    /**
     * CreateOrderDTO constructor.
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
        ?GetCouponDTO $coupon = null,
        string $vat_detail,
        string $vat_value
    ) {
        $this->order_id = $order_id;
        $this->user = $user;
        $this->cart = $cart;
        $this->total_price = $total_price;
        $this->order_payment = $order_payment;
        $this->coupon = $coupon;
        $this->vat_detail = $vat_detail;
        $this->vat_value = $vat_value;
    }

    /**
     * Creates a new CreateOrderDTO from an Order model.
     *
     * @param  Order  $order  The Order model instance.
     * @param  string  $user_id The ID of the user who placed the order.
     * @return static  A new CreateOrderDTO instance.
     */
    public static function fromModel(Order $order, $user_id): static
    {
        $user = User::find($user_id);
        $userDTO = GetUserDTO::fromModel($user);
        $cart = Cart::find($order->cart_id);
        $coupon = null;
        if($order->coupon != null) {
            $coupon = GetCouponDTO::fromModel($order->coupon);
        }
        return new static(
            $order->order_id,
            $userDTO,
            GetCartDTO::fromModel($cart, $user_id),
            $order->order_price,
            $order->order_payment,
            $coupon,
            $order->vat_detail,
            $order->vat_value
        );
    }
}
