<?php

namespace App\DTOs;

use App\Services\UserService;
use Illuminate\Database\Eloquent\Collection;

class SearchDTO
{
    public array $products;
    public array $categories;
    public array $brands;
    public array $posts;

    public function __construct(array $products , array $categories, array $brands, array $posts)
    {
        $this->products = $products;
        $this->categories = $categories;
        $this->brands = $brands;
        $this->posts = $posts;
    }

    public static function create(Collection $products , Collection $categories, Collection $brands, Collection $posts, UserService $userService, GetUserDTO $user = null): self
    {
        $productArr = [];
        $categoriesArr = [];
        $brandsArr = [];
        $postsArr = [];
        if($user != null){
            foreach ($products as $product) {
                $productArr[] = GetProductDTO::fromModel($product, $user);
            }
        }
        else {
            foreach ($products as $product) {
                $productArr[] = GetProductDTO::fromModel($product, null);
            }
        }
        
        foreach ($categories as $category) {
            $categoriesArr[] = GetCategoryDTO::fromModel($category);
        }
        foreach ($brands as $brand) {
            $brandsArr[] = GetCategoryDTO::fromModel($brand);
        }
        foreach ($posts as $post) {
            $postsArr[] = PostPageDTO::fromModel($post, $userService);
        }
        return new self($productArr, $categoriesArr, $brandsArr, $postsArr);
    }
}