<?php

namespace App\DTOs;

use App\Models\Cart;
use App\Services\CartService;

/**
 * Data Transfer Object for representing a cart after a product removal.
 */
class RemoveProductCartDTO
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
     * An array of GetProductDTO representing the products in the cart.
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
     * Constructor for RemoveProductCartDTO.
     *
     * @param string $cart_id The ID of the cart.
     * @param GetUserDTO $user The user associated with the cart.
     * @param array $products An array of GetProductDTO representing the products in the cart.
     * @param string $price The total price of the cart.
     */
    public function __construct(string $cart_id, GetUserDTO $user, array $products, $price)
    {
        $this->cart_id = $cart_id;
        $this->user = $user;
        $this->products = $products;
        $this->price = $price;
    }

    /**
     * Creates a new RemoveProductCartDTO from a Cart model and user information.
     *
     * @param Cart $cart The Cart model instance.
     * @param GetUserDTO $user The user associated with the cart.
     * @return static A new RemoveProductCartDTO instance.
     */
    public static function fromModel(Cart $cart, GetUserDTO $user): static
    {
        return new static(
            $cart->cart_id,
            $user,
            GetProductDTO::fromModels($cart->products()->get(), $user),
            CartService::totalPrice($cart->products()->get())
        );
    }
}
