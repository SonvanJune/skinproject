<?php

namespace App\Services;

use App\DTOs\GetProductDTO;
use App\DTOs\GetUserDTO;
use App\DTOs\InsertProductToWishlistDTO;
use App\DTOs\PaginatedDTO;
use App\DTOs\RemoveProductInWishlistDTO;
use App\Models\Product;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class WishlistService
{
    private const PER_PAGE = 15; // Number of items per page for pagination
    private const DEFAULT_PAGE = 1; // Default page number for pagination

    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Adds a product to the user's wishlist.
     *
     * @param Request $request The incoming HTTP request containing the product_id.
     * @return string|InsertProductToWishlistDTO Returns a string error message on failure or an InsertProductToWishlistDTO on success.
     */
    public function insertProductToWishlist(Request $request, User $user): string|InsertProductToWishlistDTO
    {
        $validator = Validator::make(
            $request->all(),
            [
                'product_id' => 'required|uuid',
            ]
        );

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        if (!Product::where("product_id", $request->input("product_id"))->exists()) {
            return "Product not exists";
        }

        if (Wishlist::where(["user_id" => $user->user_id, "product_id" => $request->input("product_id")])->exists()) {
            DB::beginTransaction();
            try {
                $user->products()->detach($request->input("product_id"));
                $flag = $user->save();

                DB::commit();

                if (!$flag) {
                    return "Cannot remove product from wishlist";
                }

                return InsertProductToWishlistDTO::fromModel("Removed Product From Wishlist");
            } catch (\Exception $e) {
                DB::rollBack();
                return 'Failed to add product to wishlist: ' . $e->getMessage();
            }
        }
        else{
            DB::beginTransaction();
            try {
                $user->products()->attach($request->input("product_id"));
                $flag = $user->save();
    
                DB::commit();
    
                if (!$flag) {
                    return "Cannot add product to wishlist";
                }
    
                return InsertProductToWishlistDTO::fromModel("Added Product To Wishlist");
            } catch (\Exception $e) {
                DB::rollBack();
                return 'Failed to add product to wishlist: ' . $e->getMessage();
            }
        }
    }

    /**
     * Retrieves the user's wishlist with pagination.
     *
     * @param Request $request The incoming HTTP request containing page and per_page parameters.
     * @return PaginatedDTO|string Returns a PaginatedDTO containing the wishlist items and pagination details, or a string error message on failure.
     */
    public function readUserWishlist(Request $request, ?GetUserDTO $user): PaginatedDTO|string
    {
        $validator = Validator::make(
            $request->all(),
            [
                'page' => 'nullable|numeric|integer',
                'per_page' => 'nullable|numeric|integer'
            ]
        );

        if ($validator->fails()) {
            return implode("\n", $validator->errors()->all());
        }

        $userModel = User::find($user->user_id);

        $perPage = $request->input('per_page', $this::PER_PAGE);
        $page = $request->input('page', $this::DEFAULT_PAGE);
        $skip = ($page - 1) * $perPage;

        $wishlist = $userModel->products()->skip($skip)->take($perPage)->get();

        if ($wishlist->isEmpty()) {
            return 'Wish list not found';
        }

        $total = $userModel->products()->count();

        return PaginatedDTO::fromData(GetProductDTO::fromModels($wishlist, $user), $page, $perPage, $total);
    }
}
