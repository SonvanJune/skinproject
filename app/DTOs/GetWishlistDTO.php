<?php

namespace App\DTOs;

use App\Models\Product;
use App\Models\Wishlist;

class GetWishlistDTO
{
    public GetUserDTO $user;
    public Product $product;

    public function __construct(GetUserDTO $user, Product $product)
    {
        $this->user = $user;
        $this->product = $product;
    }

    public static function fromModel(Wishlist $wishlist): self|string
    {
        $user = $wishlist->user();
        $product = $wishlist->product();

        if (!$user) {
            return "Invalid user";
        }

        if (!$product) {
            return "Invalid product";
        }

        return new self(
            GetUserDTO::fromModel($user),
            $product
        );
    }

    public static function fromModels(array $users): array
    {
        $result = [];
        foreach ($users as $user) {
            $result[] = self::fromModel($user);
        }
        return $result;
    }
}
