<?php

namespace App\DTOs;

use App\Models\Cart;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Support\Collection;

/**
 * Data Transfer Object for representing a shopping cart.
 */
class GetCartDTO
{
    /**
     * The ID of the cart.
     *
     * @var string
     */
    public string $cart_id;

    /**
     * The user associated with the cart.
     *
     * @var GetUserDTO
     */
    public GetUserDTO $user;

    /**
     * The products in the cart.
     *
     * @var array
     */
    public array $products;

    /**
     * The total price of the cart.
     *
     * @var string
     */
    public string $price;

    /**
     * The total price of the cart.
     *
     * @var string
     */
    public string $priceOfVat;

    /**
     * The status of the cart.
     *
     * @var int
     */
    public int $status;

    /**
     * GetCartDTO constructor.
     *
     * @param string $cart_id The ID of the cart.
     * @param GetUserDTO $user The user associated with the cart.
     * @param array $products The products in the cart.
     * @param string $price The total price of the cart.
     * @param int $status The status of the cart.
     */
    public function __construct(string $cart_id, GetUserDTO $user, array $products, string $price,string $priceOfVat, int $status)
    {
        $this->cart_id = $cart_id;
        $this->user = $user;
        $this->products = $products;
        $this->price = $price;
        $this->priceOfVat = $priceOfVat;
        $this->status = $status;
    }

    /**
     * Creates a new GetCartDTO from a Cart model.
     *
     * @param Cart $cart The Cart model instance.
     * @param GetUserDTO $user The user associated with the cart.
     * @return static A new GetCartDTO instance.
     */
    public static function fromModel(Cart $cart, $user_id): static
    {
        $userModel = User::where("user_id", $user_id)->first();
        $user = GetUserDTO::fromModel($userModel);
        return new static(
            $cart->cart_id,
            $user,
            GetProductDTO::fromModels($cart->products()->get(), $user),
            CartService::totalPrice($cart->products()->get()),
            CartService::totalPriceOfVat($cart->products()->get()),
            $cart->cart_status
        );
    }

    /**
     * Creates an array of GetCartDTO from a collection of Cart models.
     *
     * @param Collection $carts The collection of Cart model instances.
     * @return array An array of GetCartDTO instances.
     */
    public static function fromModels(Collection $carts): array
    {
        $result = [];
        foreach ($carts as $cart) {
            $user = GetUserDTO::fromModel($cart->user);
            $result[] = new static(
                $cart->cart_id,
                $user,
                GetProductDTO::fromModels($cart->products()->get(), $user),
                CartService::totalPrice($cart->products()->get()),
                CartService::totalPriceOfVat($cart->products()->get()),
                $cart->cart_status
            );
        }
        return $result;
    }
}
