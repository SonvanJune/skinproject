<?php

namespace App\DTOs;

use App\Models\Cart;

class CreateCartDTO
{
    public string $cart_id;
    public GetUserDTO $user;
    public array $products;
    public string $price;

    /**
     * CreateCartDTO constructor.
     *
     * @param string $cart_id The ID of the cart.
     * @param GetUserDTO $user The ID of the user.
     * @param array $products Array of the products.
     */
    public function __construct(string $cart_id, GetUserDTO $user, array $products)
    {
        $this->cart_id = $cart_id;
        $this->user = $user;
        $this->products = $products;
        $this->price = 0;
    }

    /**
     * Creates a new CreateCartDTO from a Cart model.
     *
     * @param Cart $cart The Cart model instance.
     * @return self A new CreateCartDTO instance.
     */
    public static function fromModel(Cart $cart, GetUserDTO $user): self
    {
        return new self(
            $cart->cart_id,
            $user,
            GetProductDTO::fromModels($cart->products()->get(), $user),
        );
    }
}
